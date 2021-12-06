<?php

namespace App\Http\Controllers\alq_car;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\alq_car;
use App\Services\alq_car\alq_carService;
use Illuminate\Support\Facades\DB;
use App\Core\ReportService;
use Carbon\Carbon;
use App\Http\Mesh\ServicesMesh;
use App\Http\Mesh\UserService;
use App\Http\Mesh\UsuService;
use Dompdf\Dompdf;
use Dompdf\Options;
use GuzzleHttp\Client;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;



/** @property alq_carService $service */
class alq_carController extends CrudController
{
   
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
        $alqu->descri='N/A';
        $headers = ["Authorization" => $request->input('token')];
        $index=[
            'FECHA'=>'fecha',
            'HORA DE ENTRADA'=>'descri',
            'Hoyo de salida'=>'namehole',
            'N° DE SOCIO'=>'user_num',
            'TIPO DE SOCIO'=>'tipo_p',
            'CATEGORIA DE SOCIO'=>'tipo_p',
            'N° DE SOCIO QUE INVITA'=>'can_p',
            'NOMBRE DEL SOCIO QUE INVITA'=>'hol_id',
            'NOMBRE DE SOCIO / INVITADO /DEPENDIENTE/RECIPROCIDAD'=>'numcar',
            'SOCIO / INVITADO / REC.'=>'obs',
            'NOMBRE DE SOCIO / INVITADO /DEPENDIENTE/RECIPROCIDAD'=>'numcar',
            'NUMERO DE CARNET DE INVITADOS'=>'numcar',
            'RECUENTO DE RONDAS'=>'numcar',
            'HORA DE INICIO JUEGO'=>'numcar',
            'HOYO SALIDA'=>'numcar',
            '# CARRITO'=>'numcar',
            'GRUPO RONDA'=>'numcar',
            'CANTIDAD DE HOYOS JUGADOS'=>'numcar',
            'CARRITO / CAMINANDO'=>'numcar',
            'OBSERVACIONES'=>'numcar',

        ];
        $info []=$alqu;
        $dat=$now;
        $report = new ReportService();
        $report->indexPerSheet([$index]);
        $report->dataPerSheet($info);
        $report->index($index);
        $report->data($alqu);
        //$report->external();
        return $report->report("automatic","Alquiler de carritos",null,null,false,1,true);
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
        if($request->fecha_ini != null && $request->fecha_ini != '' && $request->fecha_fin != null && $request->fecha_fin != ''){
            $band = true;

            $inicio = Carbon::parse($request->fecha_ini)->startOfDay();
            $fin = Carbon::parse($request->fecha_fin)->endOfDay();
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
                                return $query->where('alq_car.id_hole',$hol_id);
                            })
                            ->when($request->codegroup,function($query,$codegroup){
                                return $query->where('group.cod','ilike',"$codegroup");
                            });
            if($band){
                $operations = $operations->whereBetween('fecha',[$inicio,$fin]);
            }
        $operations = $operations->get();      
        
        return ["list" => $operations, "total" => $operations->count()];
    }


    


public function rezero(Request $request)
{
   
    $alqu=DB::table('alq_car')
    ->join('group','group.id','=','alq_car.gro_id')
    ->join('cars_golf','cars_golf.id','=','alq_car.car_id')
    ->join('holes','holes.id','=','alq_car.id_hole')
    ->leftJoin('guests','guests.id','=','alq_car.user_id')
    ->select('guests.host_number as invnumsoc','guests.host_name as invnamesoc','guests.card_number as carnet','group.cod as codegroup','cars_golf.cod as numcar','holes.name as namehole','alq_car.user_id','alq_car.user_num','alq_car.user_name','alq_car.car_id','alq_car.hol_id','alq_car.gro_id',DB::Raw('cast(alq_car.fecha as date)'),'alq_car.id_hole','alq_car.obs','alq_car.tipo_p','alq_car.can_p','alq_car.created_at')
    ->orderBy('date','codegroup')
    ->when($request->dat, function($query, $interval){
        $date = explode('_', $interval);
        $date[0] = Carbon::parse($date[0])->format('Y-m-d');
        $date[1] = Carbon::parse($date[1])->format('Y-m-d');
        return $query->whereBetween(
            DB::raw("TO_CHAR(alq_car.created_at,'YYYY-MM-DD')"),[$date[0],$date[1]]);
        })
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
    ->when($request->j,function($query,$hol_id){
        //Buscar por id del hoyo
        return $query->where('alq_car.id_hole',$hol_id);
    })
    ->when($request->codegroup,function($query,$codegroup){
        return $query->where('group.cod','ilike',"$codegroup");
    })
    ->get(); 

 //dd($alqu);
 $ser=new UsuService();
        $resp=$ser->getcategory();

        foreach($alqu as $ronditas)
        {
            foreach($resp as $tuser)
            {
                if($ronditas->user_id==$tuser->id)
                {
                    $ronditas->categoria=$tuser->category_type_name;
                    $ronditas->clase=$tuser->clase_usuario;
                }
            }
        }
  
    $excel=new Spreadsheet();
    $hoja=$excel->getActiveSheet();
    $hoja->setTitle("Alquiler de carritos");
    $hoja->setCellValue('A1','FECHA');
    $hoja->setCellValue('B1','N° DE SOCIO');
    $hoja->setCellValue('C1','TIPO DE SOCIO');
    $hoja->setCellValue('D1','CATEGORIA DE SOCIO');
    $hoja->setCellValue('E1','N° DE SOCIO QUE INVITA');
    $hoja->setCellValue('F1','NOMBRE DEL SOCIO QUE INVITA');
    $hoja->setCellValue('G1','NOMBRE DE SOCIO / INVITADO /DEPENDIENTE/RECIPROCIDAD');
    $hoja->setCellValue('H1','SOCIO / INVITADO / REC.');
    $hoja->setCellValue('I1','NUMERO DE CARNET DE INVITADOS');
    $hoja->setCellValue('J1','RECUENTO DE RONDAS');
    $hoja->setCellValue('K1','HORA DE INICIO JUEGO');
    $hoja->setCellValue('L1','HOYO SALIDA');
    $hoja->setCellValue('M1','# CARRITO');
    $hoja->setCellValue('N1','GRUPO RONDA');
    $hoja->setCellValue('O1','CANTIDAD DE HOYOS JUGADOS');
    $hoja->setCellValue('P1','Observaciones');


    $excel->getActiveSheet()->getStyle('A1:P1')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setRGB('0066CC');

    $excel->getActiveSheet()->getStyle('A1:P1')->getFont()
        ->applyFromArray( [ 'name' => 'Arial', 'bold' => TRUE, 'italic' => FALSE,'strikethrough' => FALSE, 'color' => [ 'rgb' => 'ffffff' ] ] );
    
    $excel->getActiveSheet()->getRowDimension('1')->setRowHeight(80, 'pt');

    $excel->getActiveSheet()->getColumnDimension('A')->setWidth(160, 'px');
    //$excel->getActiveSheet()->getColumnDimension('A')->setWidth(20, 'px');
    $excel->getActiveSheet()->getColumnDimension('B')->setWidth(170, 'px');
    $excel->getActiveSheet()->getColumnDimension('C')->setWidth(170, 'px');
    $excel->getActiveSheet()->getColumnDimension('D')->setWidth(200, 'px');
    $excel->getActiveSheet()->getColumnDimension('E')->setWidth(230, 'px');
    $excel->getActiveSheet()->getColumnDimension('F')->setWidth(270, 'px');
    $excel->getActiveSheet()->getColumnDimension('G')->setWidth(475, 'px');
    $excel->getActiveSheet()->getColumnDimension('H')->setWidth(320, 'px');
    $excel->getActiveSheet()->getColumnDimension('I')->setWidth(330, 'px');
    $excel->getActiveSheet()->getColumnDimension('J')->setWidth(330, 'px');
    $excel->getActiveSheet()->getColumnDimension('K')->setWidth(270, 'px');
    $excel->getActiveSheet()->getColumnDimension('L')->setWidth(320, 'px');
    $excel->getActiveSheet()->getColumnDimension('M')->setWidth(230, 'px');
    $excel->getActiveSheet()->getColumnDimension('N')->setWidth(200, 'px');
    $excel->getActiveSheet()->getColumnDimension('O')->setWidth(270, 'px');
    $excel->getActiveSheet()->getColumnDimension('P')->setWidth(350, 'px');
    

    $excel->getActiveSheet()->getStyle('A:P')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $excel->getActiveSheet()->getStyle('A:P')
    ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    
    
    $fila=2;
    $ser=new UsuService();
    foreach($alqu as $rows)
    {
            
        $hoja->setCellValue('A'.$fila,$rows->fecha);
        $hoja->setCellValue('B'.$fila,$rows->user_num);
        if(array_key_exists('categoria', $rows))
        {
            $hoja->setCellValue('C'.$fila,$rows->categoria);
        }
        else
        {
            $hoja->setCellValue('C'.$fila,'');
        }

        if(array_key_exists('clase', $rows))
        {
            $hoja->setCellValue('D'.$fila,$rows->clase);
        }
        else
        {
            $hoja->setCellValue('D'.$fila,'');
        }
       
    
        if($rows->invnumsoc!==null)
        {
            $hoja->setCellValue('E'.$fila,$rows->invnumsoc);
        }
        else
        {
            $hoja->setCellValue('E'.$fila,'N/A');
        }

        if($rows->invnamesoc!==null)
        {
            $hoja->setCellValue('f'.$fila,$rows->invnamesoc);
        }
        else
        {
            $hoja->setCellValue('F'.$fila,'N/A');
        }
        
        $hoja->setCellValue('G'.$fila,$rows->user_name);
        $hoja->setCellValue('H'.$fila,$rows->tipo_p);
        if($rows->carnet!==null)
        {
            $hoja->setCellValue('I'.$fila,$rows->carnet);
        }
        else
        {
            $hoja->setCellValue('I'.$fila,'N/A');
        }
        $hoja->setCellValue('J'.$fila,'1');
        $hoja->setCellValue('K'.$fila,$rows->codegroup);
        $hoja->setCellValue('L'.$fila,$rows->namehole);
        $hoja->setCellValue('M'.$fila,$rows->numcar);
        $hoja->setCellValue('N'.$fila,$rows->can_p);
        $hoja->setCellValue('O'.$fila,$rows->hol_id);
        $hoja->setCellValue('P'.$fila,$rows->obs);
        


        $fila++;
            
    }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="reporte.xlsx"');
        $writer=IOFactory::createWriter($excel,'Xlsx');
        $writer->save("php://output");
        exit;

    
}


public function topday($year,$month,$i,$tipo)
{
    $outputs = DB::table('alq_car')->select(['user_id','user_name',DB::raw('Count(user_id) as recuento')])->groupBy(['user_id','user_name'])
    ->where('tipo_p',$tipo)->whereYear('created_at', $year) ->whereMonth('created_at',$month)
    ->whereDay('created_at', $i)->limit('10')->orderBy('recuento','desc')->get();

    return ["list"=>$outputs,"total"=>count($outputs)];
}



    

public function topmes($year, $i,$tipo)
    {
        $ust=DB::table('alq_car')->select(['user_id','user_name',DB::raw('Count(user_id) as recuento')])->groupBy(['user_id','user_name'])
        ->where('tipo_p',$tipo)->whereYear('created_at', $year)->whereMonth('created_at',$i)
        ->limit('10')->orderBy('recuento','desc')->get();

        return ["list"=>$ust,"total"=>count($ust)];

    }

    public function indicadormes($year,$i)
    {
        $ronda=DB::table('alq_car')->select(['user_id',DB::raw('Count(user_id) as recuento')])->groupBy(['user_id'])
        ->whereYear('created_at', $year)->whereMonth('created_at',$i)->orderBy('recuento','desc')->get();
        $ser=new UsuService();
        $resp=$ser->getcategory();

        foreach($ronda as $ronditas)
        {
            foreach($resp as $tuser)
            {
                if($ronditas->user_id==$tuser->id)
                {
                    $ronditas->categoria=$tuser->category_type_name;
                }
            }
        }

        $cont = [];
    $c2 = 0;
    $cuenta=0;
    foreach ($ronda as $ronditas){
            if(array_key_exists('categoria', $ronditas)){
                $cuenta++;
              if(array_key_exists($ronditas->categoria, $cont)){
                  $c2 = $cont[$ronditas->categoria];
              }else{
                  $c2 = $cont[$ronditas->categoria] = 0;
              }
              $cont[$ronditas->categoria] = $c2 + 1;
            }   
    }

    $vista = [];
    foreach($cont as $c=>$l){
      $vista[] = [
          "name"=>$c,
          "value"=>$l
      ];
    }
          return ["list"=>$vista,"total"=>$cuenta];
        //return ["list"=>$cont,"total"=>$cuenta];
    }
 


    public function indicadorday($year,$month,$i)
    {
        $ronda=DB::table('alq_car')->select(['user_id',DB::raw('Count(user_id) as recuento')])->groupBy(['user_id'])
        ->whereYear('created_at', $year) ->whereMonth('created_at',$month)
        ->whereDay('created_at', $i)->orderBy('recuento','desc')->get();
        $ser=new UsuService();
        $resp=$ser->getcategory();

        foreach($ronda as $ronditas)
        {
            foreach($resp as $tuser)
            {
                if($ronditas->user_id==$tuser->id)
                {
                    $ronditas->categoria=$tuser->category_type_name;
                }
            }
        }

        $cont = [];
    $c2 = 0;
    $cuenta=0;
    foreach ($ronda as $ronditas){
            if(array_key_exists('categoria', $ronditas)){
                $cuenta++;
              if(array_key_exists($ronditas->categoria, $cont)){
                  $c2 = $cont[$ronditas->categoria];
              }else{
                  $c2 = $cont[$ronditas->categoria] = 0;
              }
              $cont[$ronditas->categoria] = $c2 + 1;
            }   
    }

    $vista = [];
    foreach($cont as $c=>$l){
      $vista[] = [
          "name"=>$c,
          "value"=>$l
      ];
    }
          return ["list"=>$vista,"total"=>$cuenta];
        //return ["list"=>$cont,"total"=>$cuenta];
    }
 
    
//sirve para exportar a excel el top de rondas 
public function topdayreport($year,$month,$i,$tipo,Request $request)
{
    $outputs = DB::table('alq_car')->select(['user_id','user_name','user_num',DB::raw('Count(user_id) as recuento')])->groupBy(['user_id','user_name','user_num'])
    ->where('tipo_p',$tipo)->whereYear('created_at', $year) ->whereMonth('created_at',$month)
    ->whereDay('created_at', $i)->orderBy('recuento','desc')->get();

    $excel=new Spreadsheet();
    $hoja=$excel->getActiveSheet();
    $hoja->setTitle("Top Rondas");
    $hoja->mergeCells('A1:D1');
    $hoja->setCellValue('A1','Top Rondas '.$tipo.' '.$year.'/'.$month.'/'.$i.'');
    $hoja->setCellValue('A2','#');
    $hoja->setCellValue('B2','N° DE SOCIO');
    $hoja->setCellValue('C2','NOMBRE');
    $hoja->setCellValue('D2','RONDAS');

    $excel->getActiveSheet()->getStyle('A1:D1')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setRGB('12AE0D');

    $excel->getActiveSheet()->getStyle('A2:D2')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setRGB('0066CC');

    $excel->getActiveSheet()->getStyle('A1:D1')->getFont()
        ->applyFromArray( [ 'name' => 'Arial', 'bold' => TRUE, 'italic' => FALSE,'strikethrough' => FALSE,'size'=>18, 'color' => [ 'rgb' => 'ffffff' ] ] );

    $excel->getActiveSheet()->getStyle('A2:D2')->getFont()
        ->applyFromArray( [ 'name' => 'Arial', 'bold' => TRUE, 'italic' => FALSE,'strikethrough' => FALSE, 'color' => [ 'rgb' => 'ffffff' ] ] );
        $excel->getActiveSheet()->getRowDimension('1')->setRowHeight(50, 'pt');
    $excel->getActiveSheet()->getRowDimension('2')->setRowHeight(50, 'pt');

    $excel->getActiveSheet()->getColumnDimension('A')->setWidth(160, 'px');
    $excel->getActiveSheet()->getColumnDimension('B')->setWidth(170, 'px');
    $excel->getActiveSheet()->getColumnDimension('C')->setWidth(170, 'px');
    $excel->getActiveSheet()->getColumnDimension('D')->setWidth(200, 'px');
    

    $excel->getActiveSheet()->getStyle('A:D')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $excel->getActiveSheet()->getStyle('A:D')
    ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

   
    
    
    $fila=3;
    $ser=new UsuService();
    foreach($outputs as $rows)
    {
            
        $hoja->setCellValue('A'.$fila,$rows->user_id);
        if($rows->user_num!==null)
        {
            $hoja->setCellValue('B'.$fila,$rows->user_num);
        }
        else
        {
            $hoja->setCellValue('B'.$fila,'N/A');
        }
        $hoja->setCellValue('C'.$fila,$rows->user_name);
        $hoja->setCellValue('D'.$fila,$rows->recuento);
       
        $fila++;
            
    }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="reporte top day.xlsx"');
        $writer=IOFactory::createWriter($excel,'Xlsx');
        $writer->save("php://output");
        exit;

    
}


public function topmesreport($year,$i,$tipo,Request $request)
{
    $ust=DB::table('alq_car')->select(['user_id','user_name','user_num',DB::raw('Count(user_id) as recuento')])->groupBy(['user_id','user_name','user_num'])
    ->where('tipo_p',$tipo)->whereYear('created_at', $year)->whereMonth('created_at',$i)
    ->orderBy('recuento','desc')->get();


    $excel=new Spreadsheet();
    $hoja=$excel->getActiveSheet();
    $hoja->setTitle("Top Rondas");
    $hoja->mergeCells('A1:D1');
    $hoja->setCellValue('A1','Top Rondas '.$tipo.' '.$year.'/'.$i.'');
    $hoja->setCellValue('A2','#');
    $hoja->setCellValue('B2','N° DE SOCIO');
    $hoja->setCellValue('C2','NOMBRE');
    $hoja->setCellValue('D2','RONDAS');

    $excel->getActiveSheet()->getStyle('A1:D1')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setRGB('12AE0D');

    $excel->getActiveSheet()->getStyle('A2:D2')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setRGB('0066CC');

    $excel->getActiveSheet()->getStyle('A1:D1')->getFont()
        ->applyFromArray( [ 'name' => 'Arial', 'bold' => TRUE, 'italic' => FALSE,'strikethrough' => FALSE,'size'=>18, 'color' => [ 'rgb' => 'ffffff' ] ] );

    $excel->getActiveSheet()->getStyle('A2:D2')->getFont()
        ->applyFromArray( [ 'name' => 'Arial', 'bold' => TRUE, 'italic' => FALSE,'strikethrough' => FALSE, 'color' => [ 'rgb' => 'ffffff' ] ] );
        $excel->getActiveSheet()->getRowDimension('1')->setRowHeight(50, 'pt');
    $excel->getActiveSheet()->getRowDimension('2')->setRowHeight(50, 'pt');

    $excel->getActiveSheet()->getColumnDimension('A')->setWidth(160, 'px');
    $excel->getActiveSheet()->getColumnDimension('B')->setWidth(170, 'px');
    $excel->getActiveSheet()->getColumnDimension('C')->setWidth(170, 'px');
    $excel->getActiveSheet()->getColumnDimension('D')->setWidth(200, 'px');
    

    $excel->getActiveSheet()->getStyle('A:D')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $excel->getActiveSheet()->getStyle('A:D')
    ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

   
    
    
    $fila=3;
    $ser=new UsuService();
    foreach($ust as $rows)
    {
            
        $hoja->setCellValue('A'.$fila,$rows->user_id);
        if($rows->user_num!==null)
        {
            $hoja->setCellValue('B'.$fila,$rows->user_num);
        }
        else
        {
            $hoja->setCellValue('B'.$fila,'N/A');
        }
        $hoja->setCellValue('C'.$fila,$rows->user_name);
        $hoja->setCellValue('D'.$fila,$rows->recuento);
       
        $fila++;
            
    }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="reporte top mes.xlsx"');
        $writer=IOFactory::createWriter($excel,'Xlsx');
        $writer->save("php://output");
        exit;

    
}

public function rondastiporeportday($year,$month,$i)
{
   
    $excel=new Spreadsheet();
    $hoja=$excel->getActiveSheet();
    $hoja->setTitle("Rondas segun tipo de socio");
    $hoja->mergeCells('A1:B1');
    $hoja->setCellValue('A1','Rondas segun tipo de socio');
    $hoja->setCellValue('A2','TIPO DE SOCIO');
    $hoja->setCellValue('B2','RONDA');

    $excel->getActiveSheet()->getStyle('A1:B1')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setRGB('12AE0D');

    $excel->getActiveSheet()->getStyle('A2:B2')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setRGB('0066CC');

    $excel->getActiveSheet()->getStyle('A1:B1')->getFont()
        ->applyFromArray( [ 'name' => 'Arial', 'bold' => TRUE, 'italic' => FALSE,'strikethrough' => FALSE,'size'=>18, 'color' => [ 'rgb' => 'ffffff' ] ] );

    $excel->getActiveSheet()->getStyle('A2:B2')->getFont()
        ->applyFromArray( [ 'name' => 'Arial', 'bold' => TRUE, 'italic' => FALSE,'strikethrough' => FALSE, 'color' => [ 'rgb' => 'ffffff' ] ] );
        $excel->getActiveSheet()->getRowDimension('1')->setRowHeight(50, 'pt');
    $excel->getActiveSheet()->getRowDimension('2')->setRowHeight(50, 'pt');

    $excel->getActiveSheet()->getColumnDimension('A')->setWidth(160, 'px');
    $excel->getActiveSheet()->getColumnDimension('B')->setWidth(170, 'px');

    

    $excel->getActiveSheet()->getStyle('A:B')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $excel->getActiveSheet()->getStyle('A:B')
    ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

   
    
    
    $fila=3;
    $ronda=DB::table('alq_car')->select(['user_id',DB::raw('Count(user_id) as recuento')])->groupBy(['user_id'])
        ->whereYear('created_at', $year) ->whereMonth('created_at',$month)
        ->whereDay('created_at', $i)->orderBy('recuento','desc')->get();
    $ser=new UsuService();
    $resp=$ser->getcategory();

    foreach($ronda as $ronditas)
    {
        foreach($resp as $tuser)
        {
            if($ronditas->user_id==$tuser->id)
            {
                $ronditas->categoria=$tuser->category_type_name;
            }
        }
    }

    $cont = [];
$c2 = 0;
$cuenta=0;
foreach ($ronda as $ronditas){
        if(array_key_exists('categoria', $ronditas)){
            $cuenta++;
          if(array_key_exists($ronditas->categoria, $cont)){
              $c2 = $cont[$ronditas->categoria];
          }else{
              $c2 = $cont[$ronditas->categoria] = 0;
          }
          $cont[$ronditas->categoria] = $c2 + 1;
        }   
}

$vista = [];
foreach($cont as $c=>$l){
  $vista[] = [
      "name"=>$c,
      "value"=>$l
  ];

  $hoja->setCellValue('A'.$fila,$c);
  $hoja->setCellValue('B'.$fila,$l);
 
  $fila++;
}     

       
       
       
       
            
    


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="reporte rondas tipo.xlsx"');
        $writer=IOFactory::createWriter($excel,'Xlsx');
        $writer->save("php://output");
        exit;

    
}


public function rondastiporeportmes($year,$i)
{
   
    $excel=new Spreadsheet();
    $hoja=$excel->getActiveSheet();
    $hoja->setTitle("Rondas segun tipo de socio");
    $hoja->mergeCells('A1:B1');
    $hoja->setCellValue('A1','Rondas segun tipo de socio');
    $hoja->setCellValue('A2','TIPO DE SOCIO');
    $hoja->setCellValue('B2','RONDA');

    $excel->getActiveSheet()->getStyle('A1:B1')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setRGB('12AE0D');

    $excel->getActiveSheet()->getStyle('A2:B2')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setRGB('0066CC');

    $excel->getActiveSheet()->getStyle('A1:B1')->getFont()
        ->applyFromArray( [ 'name' => 'Arial', 'bold' => TRUE, 'italic' => FALSE,'strikethrough' => FALSE,'size'=>18, 'color' => [ 'rgb' => 'ffffff' ] ] );

    $excel->getActiveSheet()->getStyle('A2:B2')->getFont()
        ->applyFromArray( [ 'name' => 'Arial', 'bold' => TRUE, 'italic' => FALSE,'strikethrough' => FALSE, 'color' => [ 'rgb' => 'ffffff' ] ] );
        $excel->getActiveSheet()->getRowDimension('1')->setRowHeight(50, 'pt');
    $excel->getActiveSheet()->getRowDimension('2')->setRowHeight(50, 'pt');

    $excel->getActiveSheet()->getColumnDimension('A')->setWidth(160, 'px');
    $excel->getActiveSheet()->getColumnDimension('B')->setWidth(170, 'px');

    

    $excel->getActiveSheet()->getStyle('A:B')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $excel->getActiveSheet()->getStyle('A:B')
    ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

   
    
    
    $fila=3;
    $ronda=DB::table('alq_car')->select(['user_id',DB::raw('Count(user_id) as recuento')])->groupBy(['user_id'])
    ->whereYear('created_at', $year)->whereMonth('created_at',$i)->orderBy('recuento','desc')->get();
    $ser=new UsuService();
    $resp=$ser->getcategory();

    foreach($ronda as $ronditas)
    {
        foreach($resp as $tuser)
        {
            if($ronditas->user_id==$tuser->id)
            {
                $ronditas->categoria=$tuser->category_type_name;
            }
        }
    }

    $cont = [];
$c2 = 0;
$cuenta=0;
foreach ($ronda as $ronditas){
        if(array_key_exists('categoria', $ronditas)){
            $cuenta++;
          if(array_key_exists($ronditas->categoria, $cont)){
              $c2 = $cont[$ronditas->categoria];
          }else{
              $c2 = $cont[$ronditas->categoria] = 0;
          }
          $cont[$ronditas->categoria] = $c2 + 1;
        }   
}

$vista = [];
foreach($cont as $c=>$l){
  $vista[] = [
      "name"=>$c,
      "value"=>$l
  ];

  $hoja->setCellValue('A'.$fila,$c);
  $hoja->setCellValue('B'.$fila,$l);
 
  $fila++;
}     

       
       
       
       
            
    


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="reporte rondas tipo.xlsx"');
        $writer=IOFactory::createWriter($excel,'Xlsx');
        $writer->save("php://output");
        exit;

    
}


}

