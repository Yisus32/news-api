<?php

namespace App\Http\Controllers\alq_car;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\alq_car;
use App\Services\alq_car\alq_carService;
use Illuminate\Support\Facades\DB;
use App\Core\ReportService;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

/** @property alq_carService $service */
class alq_carController extends CrudController
{
    private static $data = [];
    private static $index = [];
    private static $external = false;
    private static $dataPerSheet = [];
    private static $indexPerSheet = [];
    private static $title;
    private static $name;
    private static $username;
    private static $date;
    private static $user;
    private static $log_url = null;
    private static $account = [];
    private static $orientation = "portrait";
    private static $colors = ["primary" => '#9FD5D1', "secondary" => '#f2f2f2', "auxiliary" => '#ffffff'];
    public static $report;
    public static $current_url;
    private static $returnRaw = false;
    public function __construct(alq_carService $service)
    {
        parent::__construct($service);
        $this->validateStore = [
            'car_id' => 'required',
            'hol_id' => 'required',
            'gro_id' => 'required'
            ];
    
        $this->messages = [
            "required" => "El campo ' :attribute ' es requerido"
        ];
    }


    public function filter_by_date(Request $request)
    {
        $r=$request->get('ini');
        $f=$request->get('fin');
        if($r==0 or $f==0)
        {
            return ["list"=>[],'total'=>0];
        }
        elseif( $fill=alq_car::whereBetween(DB::Raw('cast(alq_car.fecha as date)'), array($r, $f))->count()==0)
        {
            return ["list"=>[],'total'=>0];
        }
        else
        {
            $fill=alq_car::whereBetween(DB::Raw('cast(alq_car.fecha as date)'), array($r, $f))->join('group','group.id','=','alq_car.gro_id') ->join('cars_golf','cars_golf.id','=','alq_car.car_id')->join('holes','holes.id','=','alq_car.id_hole')->select('group.cod as codegroup','cars_golf.cod as numcar','holes.name as namehole','alq_car.user_id','alq_car.user_num','alq_car.user_name','alq_car.car_id','alq_car.hol_id','alq_car.gro_id','alq_car.fecha','alq_car.id_hole','alq_car.obs','alq_car.tipo_p','alq_car.can_p')->get();
            return  ["list"=>$fill,'total'=>count($fill)];
        }
       
    }

    public function report(Request $request){

        if (empty($request->star)) {
            return Response()->json(["error" => true, "message" => "la fecha es requerida"],400);
        }
        $now = Carbon::now()->timezone("America/Panama");
        $r=$request->get('star');
        $f=$request->get('end');
        $alqu= $game=DB::table('alq_car')->whereBetween(DB::Raw('cast(alq_car.fecha as date)'), array($r, $f))
        ->join('group','group.id','=','alq_car.gro_id')
        ->join('cars_golf','cars_golf.id','=','alq_car.car_id')
        ->join('holes','holes.id','=','alq_car.id_hole')
        ->select('group.cod as codegroup','cars_golf.cod as numcar','holes.name as namehole','alq_car.user_id','alq_car.user_num','alq_car.user_name','alq_car.car_id','alq_car.hol_id','alq_car.gro_id','alq_car.fecha','alq_car.id_hole','alq_car.obs','alq_car.tipo_p','alq_car.can_p')->get(); 
        
        $headers = ["Authorization" => $request->input('token')];
        $index=[
            'Fecha'=>'fecha',
            'Hora de incio'=>'codegroup',
            'Hoyo de salida'=>'namehole',
            'N° de socio'=>'user_num',
            'Jugador'=>'user_name',
            'Socio/Invitado/REC.'=>'tipo_p',
            'Grupo ronda(cantidad de personas que juegan en la ronda)'=>'can_p',
            'Cantidad de hoyos jugados'=>'hol_id',
            'Carrito de golf'=>'numcar',
            'Observaciones'=>'obs'

        ];
        $info []=$alqu;
        $dat=$now;
        $report = new ReportService();
        $report->indexPerSheet([$index]);
        $report->dataPerSheet($info);
        $report->index($index);
        $report->data($alqu);
        //$report->external();
        return $report->report("automatic","Alquiler de carritos",null,null,false,1);
    }


    public function sav(Request $request)
    {
        $now = Carbon::now()->timezone("America/Panama");
        //para guardar un solo usuario 
        if($request->user_id==null and $request->auser_id==null)
        {
            return response()->json(["error" => true, "message" => "No se puede crear un juego sin un socio o un invitado"], 400);
        }
        if($request->user_id!==null and $request->auser_id==null)
        {
            $gu= new alq_car;
            $gu->user_id=$request->user_id;
            $gu->user_name=$request->user_name;
            $gu->user_num=$request->user_num;
            $gu->car_id=$request->car_id;
            $gu->hol_id=$request->hol_id;
            $gu->gro_id=$request->gro_id;
            $gu->fecha=$now;
            $gu->id_hole=$request->id_hole;
            $gu->obs=$request->obs;
            $gu->tipo_p=$request->tipo_p;
            $gu->can_p=$request->can_p;
            $gu->save();
            return response()->json([
                "status" => 201,
                "data"=>$gu],
                201);
        }

        else
        {
            $gu= new alq_car;
            $gu->user_id=$request->user_id;
            $gu->user_name=$request->user_name;
            $gu->user_num=$request->user_num;
            $gu->car_id=$request->car_id;
            $gu->hol_id=$request->hol_id;
            $gu->gro_id=$request->gro_id;
            $gu->fecha=$now;
            $gu->id_hole=$request->id_hole;
            $gu->obs=$request->obs;
            $gu->tipo_p=$request->tipo_p;
            $gu->can_p=$request->can_p;
            $gu->save();


            $gi= new alq_car;
            $gi->user_id=$request->auser_id;
            $gi->user_name=$request->auser_name;
            $gi->user_num=$request->auser_num;
            $gi->car_id=$request->car_id;
            $gi->hol_id=$request->hol_id;
            $gi->gro_id=$request->gro_id;
            $gi->fecha=$now;
            $gi->id_hole=$request->id_hole;
            $gi->obs=$request->obs;
            $gi->tipo_p=$request->atipo_p;
            $gi->can_p=$request->can_p;
            $gi->save();
            return response()->json([
                "status" => 201,
                "data1"=>$gu,
                "data2"=>$gi],
                201);
        }
    }

    public function specialFilter(Request $request)
    {
        $band = false;
        if($request->has(['fecha_ini','fecha_fin'])){
            $band = true;

            $inicio = Carbon::parse($request->fecha_ini);
            $fin = Carbon::parse($request->fecha_fin);
        }
        $operations =DB::table('alq_car')
                        ->join('group','group.id','=','alq_car.gro_id')
                        ->join('cars_golf','cars_golf.id','=','alq_car.car_id')
                        ->join('holes','holes.id','=','alq_car.id_hole')
                        ->select(
                            'alq_car.id',
                            'group.cod as codegroup',
                            'cars_golf.cod as numcar',
                            'holes.name as namehole',
                            'alq_car.user_id',
                            'alq_car.user_num',
                            'alq_car.user_name',
                            'alq_car.car_id',
                            'alq_car.hol_id',
                            'alq_car.gro_id',
                            'alq_car.fecha',
                            'alq_car.id_hole',
                            'alq_car.obs',
                            'alq_car.tipo_p',
                            'alq_car.can_p'
                            )
                            ->when($request->num, function($query,$num){
                                //buscar por numero de socio
                                return $query->where('alq_car.user_num',$num);
                            })
                            ->when($request->nom, function($query,$nom){
                                //buscar por nombre
                                return $query->where('alq_car.user_name','ILIKE',$nom);
                            })
                            ->when($request->car, function($query,$car){
                                //buscar por carrito
                                return $query->where('alq_car.car_id','ILIKE',$car);
                            })
                            ->when($request->hora, function($query,$hora){
                                //buscar numero
                                return $query->where('alq_car.gro_id','ILIKE',$hora);
                            })
                            ->when($request->tipo_p,function($query,$tipo_p){
                                //Buscar por tipo de usuario
                                return $query->where('alq_car.tipo_p','ilike',$tipo_p);
                            })
                            ->when($request->hol_id,function($query,$hol_id){
                                //Buscar por id del hoyo
                                return $query->where('alq_car.hol_id',$hol_id);
                            });
            if($band){
                $operations = $operations->whereBetween('fecha',[$inicio,$fin]);
            }
        $operations = $operations->get();      
        
        return ["list" => $operations, "total" => $operations->count()];
    }


    public function repo(Request $request)
    {
        if (empty($request->star)) {
            return Response()->json(["error" => true, "message" => "la fecha es requerida"],400);
        }

        self::$index=[
            'Fecha'=>'fecha',
            'Hora de incio'=>'codegroup',
            'Hoyo de salida'=>'namehole',
            'N° de socio'=>'user_num',
            'Jugador'=>'user_name',
            'Socio/Invitado/REC.'=>'tipo_p',
            'Grupo ronda(cantidad de personas que juegan en la ronda)'=>'can_p',
            'Cantidad de hoyos jugados'=>'hol_id',
            'Carrito de golf'=>'numcar',
            'Observaciones'=>'obs'

        ];
        $now = Carbon::now()->timezone("America/Panama");
        $r=$request->get('star');
        $f=$request->get('end');
        $alqu= $game=DB::table('alq_car')->whereBetween(DB::Raw('cast(alq_car.fecha as date)'), array($r, $f))
        ->join('group','group.id','=','alq_car.gro_id')
        ->join('cars_golf','cars_golf.id','=','alq_car.car_id')
        ->join('holes','holes.id','=','alq_car.id_hole')
        ->select('group.cod as codegroup','cars_golf.cod as numcar','holes.name as namehole','alq_car.user_id','alq_car.user_num','alq_car.user_name','alq_car.car_id','alq_car.hol_id','alq_car.gro_id','alq_car.fecha','alq_car.id_hole','alq_car.obs','alq_car.tipo_p','alq_car.can_p')->get(); 
        self::$data[]=$alqu;
        $spreadsheet = new Spreadsheet();
        $sheet = self::getDefaultConfiguration($spreadsheet);

        foreach (self::$index as $title => $value) {
            $arrayData[0][] = $title;
        }

        foreach (self::$data as $key) {
            $i = 1;
            $toArray = (is_object($key) ? $key : (is_array($key) ? (object)$key : null));
            foreach (self::$index as $title => $value) {
                $toExcel[$i] = $toArray->$value ?? null;
                $i++;
            }
            $arrayData[] = $toExcel;
        }
        $sheet->getActiveSheet()->fromArray($arrayData, "Sin Registro", 'A7');
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    
        //Todo esto de aqui abajo es para que se fuerce la descarga al ir al endpoint
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="reporte.xlsx"');
        $writer->save("php://output"); 
        return null;
    }


    protected static function getDefaultConfiguration(Spreadsheet $spreadsheet, $pathLogo = null, $columnStart = "A", $rowStart = '1')
    {
        try {
            $alphabet = range('A', 'Z');
            $totalColumns = count(self::$index) - 1;
            $totalRows = count(self::$data) + 2;

            for ($i = "A"; $i < "Z"; $i++) {
                $spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(true);

            }

            $spreadsheet->getActiveSheet()->setCellValue($columnStart . $rowStart, "Reporte de " . self::$title);
            $spreadsheet->getActiveSheet()->mergeCells($columnStart . $rowStart . ':' . $alphabet[$totalColumns] . '5');
            $spreadsheet->getActiveSheet()->getStyle($columnStart . $rowStart)->getFont()->setSize(16);
            $spreadsheet->getActiveSheet()->getStyle($columnStart . $rowStart)->getAlignment()
                ->applyFromArray([
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]);

            $spreadsheet->getActiveSheet()->getStyle($columnStart . $totalRows . ':' . $alphabet[$totalColumns] . $totalRows)
                ->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);
            $spreadsheet->getActiveSheet()->getStyle($columnStart . $rowStart . ':' . $alphabet[$totalColumns] . '1')->getFill()->setFillType(Fill::FILL_SOLID);
            $spreadsheet->getActiveSheet()->getStyle('A1:' . $alphabet[$totalColumns] . '1')->getFont()->getColor()->setARGB('00000000');
            return $spreadsheet;
        } catch (Exception $exception) {
            Log::critical($exception->getMessage() . $exception->getLine() . $exception->getFile());
            return $spreadsheet;
        }
    }
}