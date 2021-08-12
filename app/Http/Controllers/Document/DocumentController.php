<?php

namespace App\Http\Controllers\Document;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Core\ImageService;
use App\Services\Document\DocumentService;
use GoogleCloudVision\GoogleCloudVision;
use GoogleCloudVision\Request\AnnotateImageRequest;
use App\Http\Services\FirebaseService;
use App\Models\Document;
use App\Models\Guest;

/** @property DocumentService $service */
class DocumentController extends CrudController
{
    public function __construct(DocumentService $service)
    {
        parent::__construct($service);

        $this->validateStore = [
            "guest_id" => "required",
            "name"     => "required"
        ];

        $this->messages = [
            "required" => "El campo ' :attribute ' es requerido"
        ];
    }
     /**
     * Función que permite crear un documento y validarlo para los invitados
     *
     * @author foskert@gmail.com
     * @param Request $request
     * @return JsonResponse
     * @version 1.0
     */
    public function _validate(Request $request){
        $v = \Validator::make($request->all(), [
            'type'     => 'required',
            'guest_id' => 'required|numeric'
        ]);
        if (!$v->fails()){
            if($request->guest_id > 0){
                try{
                    $guest = Guest::find($request->guest_id);
                    if(empty($guest)){
                        return response()->json(array( 
                            'success' => false,
                            'message' => 'Invitado no válido',
                            'value'   => $guest,
                            'count'   => 0
                        ));
                    }
                    $document = new Document();
                    if($guest->identifier == ""){
                        if(\Validator::make($request->all(), ['document' => 'required'])->fails()){
                            return response()->json(array( 
                                'success' => false,
                                'message' => 'Se requiere del número de documento',
                                'value'   => null,
                                'count'   => 0
                            ));
                        }
                        $guest->identifier = $request->document;
                        $guest->save();
                    }
                    if(\Validator::make($request->all(), ['emission'   => 'required'])->fails()){
                        return response()->json(array( 
                            'success' => false,
                            'message' => 'Se requiere de la fecha de emisión del documento',
                            'value'   => null,
                            'count'   => 0
                        ));
                    }
                    if(\Validator::make($request->all(), ['expiration' => 'required'])->fails()){
                        return response()->json(array( 
                            'success' => false,
                            'message' => 'Se requiere de la fecha de vencimiento del documento',
                            'value'   => null,
                            'count'   => 0
                        ));
                    }
                    if(\Validator::make($request->all(), ['front_image'  => 'required'])->fails()){
                        return response()->json(array( 
                            'success' => false,
                            'message' => 'Se requiere de la imagen de frente del documento de identidad',
                            'value'   => null,
                            'count'   => 0
                        ));
                    }
                    $type = strtolower($request->type);
                    if($type == "dni"){
                        $string = $this->googleOCR($request->front_image);
                        $porAceptacion = false;
                        if(!empty($string->responses[0]->textAnnotations[0]->description)){
                            $value = $string->responses[0]->textAnnotations[0]->description;
                            $porAceptacion = $this->validateDNI($value, $guest->full_name, $guest->identifier);
                        }
                        if($porAceptacion){
                            $document->name        = $guest->full_name;
                            $document->guest_id    = $request->guest_id;
                            $document->type        = 'DNI';
                            $document->document    = $guest->identifier;
                            $document->emission    = $request->emission;
                            $document->expiration  = $request->expiration;
                            $document->state       = 'Aceptado';
                            if($this->getBase64ImageSize($request->input('front_image')) > 1){
                                return response()->json(array( 
                                    'success' => false,
                                    'message' => 'La imagen frontal supera el tamaño máximo disponible',
                                    'value'   => null ,
                                    'count'   => 0
                                ));
                            }else{
                                 $document->front_image = $this->loadImage($request->front_image, '/invitado/dni/', 'DOCUMENT_FRONT', 'invitado');
                                if(!$document->front_image){
                                    return response()->json(array(  
                                        'success'=> false,
                                        'message'=> 'No se proceso la carga de la imagen frontal',
                                        'value'  =>  $document->front_image,
                                        'count'  => 0
                                    ));
                                }   
                            }
                            if($document->save()){
                                return response()->json(array( 
                                    'success' => true,
                                    'message' => 'Registro exitoso',
                                    'value'   => $document, 
                                    'count'   => 1
                                ));
                            }else{
                                return response()->json(array( 
                                    'success' => false,
                                    'message' => 'No se logro el registro del documento',
                                    'value'   => $document, 
                                    'count'   => 0
                                ));
                            }
                        }else{
                            return response()->json(array( 
                                'success' => false,
                                'message' => 'El documento no satisface los parámetros de validación necesarios',
                                'value'   => null, 
                                'count'   => 0
                            ));
                        }
                    }else if($type == 'pasaporte'){
                        $string = $this->googleOCR($request->front_image);
                        $porAceptacion = false;
                        if(!empty($string->responses[0]->textAnnotations[0]->description)){
                            $value = $string->responses[0]->textAnnotations[0]->description;
                            $porAceptacion = $this->validatePasaporte($value, $guest->full_name, $guest->identifier, $request->expiration);
                        }
                        if($porAceptacion){
                            $document->guest_id    = $request->guest_id;
                            $document->name        = $guest->full_name;
                            $document->type        = 'Pasaporte';
                            $document->document    = $guest->identifier;
                            $document->emission    = $request->emission;
                            $document->expiration  = $request->expiration;
                            $document->state       = 'Aceptado';
                            if($this->getBase64ImageSize($request->input('front_image')) > 1){
                                return response()->json(array( 
                                    'success' => false,
                                    'message' => 'La imagen frontal supera el tamaño máximo disponible',
                                    'value'   => null ,
                                    'count'   => 0
                                ));
                            }else{
                                $front_image = $this->loadImage($request->front_image, '/invitado/pasaporte/', 'DOCUMENT_FRONT', 'Invitado');
                                if(!$front_image){
                                    return response()->json(array(  
                                        'success'=> false,
                                        'message'=> 'No se proceso la carga de la imagen',
                                        'value'  => $front_image,
                                        'count'  => 0
                                    ));
                                }   
                            }                            
                            $document->front_image = $front_image;
                            if($document->save()){
                                return response()->json(array( 
                                    'success' => false,
                                    'message' => 'Registro exitoso',
                                    'value'   => $document, 
                                    'count'   => 1
                                ));
                            }else{
                                return response()->json(array( 
                                    'success' => false,
                                    'message' => 'Error en el registro',
                                    'value'   => $document, 
                                    'count'   => 0
                                ));
                            }
                        }else{
                            return response()->json(array( 
                                'success' => false,
                                'message' => 'El pasaporte no satisface los parámetros de validación necesarios',
                                'value'   => $porAceptacion, 
                                'count'   => 0
                            ));
                        } 
                    }else{
                        return response()->json(array( 
                            'success' => false,
                            'message' => 'Tipo de documento no válido',
                            'value'   => null,
                            'count'   => 0
                        ));
                    }
                }catch (Exception $e) {
                    Log::critical('USCDCCD0001-'.$e->getMessage());
                    return response()->json(array( 
                        'success' => false,
                        'message' => 'Error USCDCCD0001 '.$e,
                        'value'   => null,
                        'count'   => 0
                    ));
                }
            }else{
                return response()->json(array( 
                    'success' => false,
                    'message' => 'Invitado no válido',
                    'value'   => null,
                    'count'   => 0
                ));
            }
        }else{
            return response()->json(array( 
                'success' => false,
                'message' => 'Parámetros no válido',
                'value'   => null,
                'count'   => 0
            ));
        }
    }
    /**
     * Valida y carga la imagen o documento mediante la creaion de la clase servicio
     *
     * @author foskert@gmail.com
     * @param String $archive
     * @param String $url
     * @param String $cons
     * @return bool|string
     * @version 2.0
    */
    public function loadImage($archive, $url = "", $cons){
        try{
            if(filter_var($archive, FILTER_VALIDATE_URL)){
                return $archive;
            }else{
                $imageService = new ImageService();
                return $imageService->document($archive,  $url, $cons); 
            }
        }catch(Exception $e){
            Log::critical('USCPBLI0001 '.$e->getMessage());
            return false;
        }
    }
    /**
     * Calcula el tamaño de la imagen en B KB MB
     *
     * @author foskert@gmail.com
     * @param String $base64Image
     * @return Exception|float|int
     * @version 2.0
    */
    public function getBase64ImageSize($base64Image){
        try{
            $size_in_bytes = (int) (strlen(rtrim($base64Image, '=')) * 3 / 4);
            $size_in_kb    = $size_in_bytes / 1024;
            return $size_in_kb / 1024;
        }catch(Exception $e){
            Log::critical('USCPB640001 '.$e->getMessage());
            return false;
        }
    }
    /**
     * Función que valida el documento de identidad con la api google cloub vision 
     *
     * @author foskert@gmail.com
     * @param file $file
     * @return Exception|float|int
     * @version 1.0
    */
    public function googleOCR($file){
        try{
            $image = base64_encode(file_get_contents($file));
            $client = new AnnotateImageRequest();
            $client->setImage($image);
            $client->setFeature("TEXT_DETECTION");
            $google_request = new GoogleCloudVision([$client],  env('GOOGLE_CLOUD_KEY'));
            return  $google_request->annotate();
        }catch(Exception $e){
            Log::critical('USCDV640001 '.$e->getMessage());
            return false;
        }
    }  
    /**
     * Función que valida el documento DNI  
     *
     * @author foskert@gmail.com
     * @param String $string
     * @param String $full_name
     * @param String $document
     * @return Exception|float|int
     * @version 1.0
    */
    public function validateDNI($string, $full_name, $document){
        $document = preg_replace("/[^0-9]/", "", $document);
        $string = str_replace('\n', ' ', $string);
        $number = str_replace(['-', '.', ','], '', $string);
        $value = strtolower($string);
        if(strpos($number, $document) === false) {
            return false;
        }else{
            $porciones = explode(" ", strtolower($full_name));
            if(empty($porciones)){
                return false;
            }
            $val = count($porciones);
            foreach ($porciones as $por) {
                if(strpos($value, $por) === false){
                    $val--; 
                }
            }
            if($val/count($porciones)*100 < 60){
                return false;
            }
            return true;
        }     
    } 
    /**
     * Función que valida el documento Pasaporte
     *
     * @author foskert@gmail.com
     * @param String $string
     * @param Object $user
     * @param Request $request
     * @return Exception|float|int
     * @version 1.0
    */ 
    public function validatePasaporte($string = "", $full_name = "", $document = "", $expiration = "" , $birthdate = ""){
        $document = preg_replace("/[^0-9]/", "", $document);
        $string = str_replace('\n', ' ', $string);
        $valueDocument = str_replace(['-', '.', ',', '<', '>'], '', $string);
        $value = strtolower($string);
        if(strpos($valueDocument, $document) == false) {
            return $document;
        }else{
            $porciones = explode(" ", strtolower($full_name));
            if(empty($porciones)){
                return false;
            }
            $val = count($porciones);
            foreach ($porciones as $por) {
                if(strpos($value, $por) === false){
                    $val--; 
                }
            }
            if($val/count($porciones)*100 < 60){
                return false;
            }
            $porciones = explode("/", $expiration);
            if(empty($porciones)){
                return false;
            }
            if(strpos($value, substr($porciones[2], -2).$porciones[1].$porciones[0]) === false){
                return false;
            }
            if($birthdate != ""){
                $birthdate = str_replace(' 00:00:00', '', $birthdate);
                $porciones = explode('-', $birthdate);
                if(empty($porciones)){
                    return false;
                }
                if(strpos($value, substr($porciones[0], -2).$porciones[1].$porciones[2]) === false){
                    return false;
                }
            }
            return true;            
        }     
    }
}