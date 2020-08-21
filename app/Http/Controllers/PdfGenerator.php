<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PdfGenerator extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function whatsapp(Request $request)
    {
      
      

      $orientation=$request->input("coverDetails")["orientation"]??"P";
      $orientation=$orientation=="LANDSCAPE"?"L":"P";
      $orientation_display=$orientation=="L"?"display:none;":"display:block";
      $orientation_empty_string=$orientation=="L"?" &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;":"";
      $orientation_empty_string_memories=$orientation=="L"?"&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;":"&ensp;&ensp;&ensp;&ensp;&ensp;";
      $format=$request->input("coverDetails")["size"]??"A4";
       
      // ini_set("memory_limit", "10056M");
        // ini_set('max_execution_time', "0");
        ini_set('max_execution_time', '300');
        ini_set("pcre.backtrack_limit", "5000000");
        // header("content-type: image/your_image_type");
        header("Content-type: text/html");
// header("Content-Disposition: inline; filename=filename.pdf");
        $files=$request->file("files");
        $text_file='';
        $images_base64=[];
        foreach($files as $file){
            $file_temp=$file->getPathName();
            $file_extension=$file->getClientOriginalExtension();
            if($file_extension=="txt"){
                $text_file=file_get_contents($file_temp);
            }
            if($file_extension=="png" || $file_extension=="jpg" || $file_extension=="giff"){
                     $file_temp_code=file_get_contents($file_temp);
                    $type = pathinfo($file_temp, PATHINFO_EXTENSION);
          $images_base64[$file->getClientOriginalName()]='data:image/' . $type . ';base64,' . base64_encode($file_temp_code);
            }
        }
     $url=storage_path('fonts\NotoColorEmoji.ttf');
        
     $coverDetails=$request->input("coverDetails");
     $sender=$coverDetails["first_person"]["name"];
     $receiver=$coverDetails["second_person"]["name"];
     $sender_image=!empty($coverDetails['first_person']['image'])?$coverDetails['first_person']['image']:file_get_contents(storage_path("app/images/male.txt"));
     $receiver_image=!empty($coverDetails['second_person']['image'])?$coverDetails['second_person']['image']:file_get_contents(storage_path("app/images/female.txt"));
     $arrows= file_get_contents(storage_path("app/images/arrows.txt"));
     // $url="/seguiemj.tff";
        $lines=explode(PHP_EOL, trim($text_file));
       $complete_message=[];
       $body_background=$request->input("cover");
       if(!empty($body_background["backgroundImage"])){
         $background_image=$body_background["backgroundImage"];
        if( $body_background['type']=="DEFAULT"){
          $background_image=file_get_contents(storage_path()."/app/images/default.txt");
        }
        $background="url($background_image)";
        // $background=$background_image;
       }
       
      
       if(!empty($body_background["backgroundColor"]) && $body_background['type']=="SOLID"){
        $background=$body_background["backgroundColor"];
       }
       if(!empty($body_background["backgroundGradientColor"]) && $body_background['type']=="GRADIENT"){
        $background=$body_background["backgroundGradientColor"];
       }
       
      //  echo $background;
      //  exit;
      $total_images=0;
      $total_messages=0;
    $message_type="sent";
       $html="
       <html>
       <head>
       <meta  content='charset=utf-8' />
       <style>
       
    
       *{
        font-family: emoji,DejaVu Sans,'Roboto' sans-serif;
 
       }
      
      /* Conversation */
      
      .conversation {
        height: calc(100% - 12px);
        position: relative;
        background: #efe7dd;
        z-index: 0;
      }
      
      .conversation ::-webkit-scrollbar {
        transition: all .5s;
        width: 5px;
        height: 1px;
        z-index: 10;
      }
      
      .conversation ::-webkit-scrollbar-track {
        background: transparent;
      }
      
      .conversation ::-webkit-scrollbar-thumb {
        background: #b3ada7;
      }
      
      .conversation .conversation-container {
        height: calc(100% - 68px);
        box-shadow: inset 0 10px 10px -10px #000000;
        overflow-x: hidden;
        padding: 0 16px;
        margin-bottom: 5px;
      }
      
      .conversation .conversation-container:after {
        content: '';
        display: table;
        clear: both;
      }
      
      /* Messages */
      
      .message {
        color: #000;
        clear: both;
        line-height: 18px;
        font-size: 15px;
        padding: 8px;
        position: relative;
        margin: 8px 0;
        max-width: 85%;
        word-wrap: break-word;
        z-index: -1;
        max-width:80%!important;
        width:70%;
        margin-top:20px;
      }
      
      .message:after {
        position: absolute;
        content: '';
        width: 0;
        height: 0;
        border-style: solid;
      }
      
      .metadata {
        display: inline-block;
        float: right;
        padding: 0 0 0 7px;
        position: relative;
        bottom: -4px;
        font-size:10px;
      }
      
      .metadata .time {
        color: rgba(0, 0, 0, .45);
        font-size: 11px;
        display: inline-block;
      }
      
      
      .message:first-child {
        margin: 16px 0 8px;
      }
      
      .message.received {
        background: #fff;
        border-radius: 0px 5px 5px 5px;
        float: left;
      }
      
      .message.received .metadata {
        padding: 0 0 0 16px;
      }
      
      .message.received:after {
        border-width: 0px 10px 10px 0;
        border-color: transparent #fff transparent transparent;
        top: 0;
        left: -10px;
      }
      
      .message.sent {
        background: #e1ffc7;
        border-radius: 5px 0px 5px 5px;
        float: right;
      }
      
      .message.sent:after {
        border-width: 0px 0 10px 10px;
        border-color: transparent transparent transparent #e1ffc7;
        top: 0;
        right: -10px;
      }
      
        .conversation {
          height: 100%;
        }
        .conversation .conversation-container {
         width:100%;
         display:flex;
         background:$background;
        }
      }
      
       </style>
       </head>
       <body style=' width:100%; height:100%;'>
       <div style='page-break-after:always;
       height:100%;
       width:100%;
       background:radial-gradient(rgba(6, 120, 6, 0.68), green);position: absolute; left:0; right: 0; top: 0; bottom: 0; z-index:-1;'>
       </div>
       <div style='page-break-after:always;
       height:100%;
       width:100%;
       z-index:2;
       '>
       <center>
       <h1>&ensp; </h1>
      </center>
       
       <center>
        <h1> 
        &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;$orientation_empty_string

        
        <sub >
<svg style='filter:drop-shadow(rgb(255, 255, 255) 0px 0px 10px);' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 80 80' width='80px' height='80px'><path fill='#f2faff' d='M7.904,58.665L7.8,58.484c-3.263-5.649-4.986-12.102-4.983-18.66 C2.826,19.244,19.577,2.5,40.157,2.5C50.14,2.503,59.521,6.391,66.57,13.446C73.618,20.5,77.5,29.879,77.5,39.855 c-0.01,20.583-16.76,37.328-37.34,37.328c-6.247-0.003-12.418-1.574-17.861-4.543l-0.174-0.096L2.711,77.636L7.904,58.665z'/><path fill='#788b9c' d='M40.157,3L40.157,3c9.85,0.003,19.105,3.838,26.059,10.799C73.17,20.76,77,30.013,77,39.855 c-0.009,20.307-16.536,36.828-36.855,36.828c-6.149-0.003-12.237-1.553-17.606-4.482l-0.349-0.19l-0.384,0.101l-18.384,4.82 l4.91-17.933l0.11-0.403l-0.209-0.362c-3.22-5.574-4.92-11.94-4.917-18.41C3.326,19.52,19.852,3,40.157,3 M40.157,2 C19.302,2,2.326,18.969,2.317,39.824C2.313,46.49,4.055,53,7.367,58.735L2,78.339l20.06-5.26 c5.526,3.015,11.751,4.601,18.084,4.604h0.016c20.855,0,37.831-16.969,37.84-37.827c0-10.108-3.933-19.613-11.077-26.764 C59.78,5.942,50.28,2.003,40.157,2L40.157,2z'/><path fill='#79ba7e' d='M39.99,70c-5.009-0.003-9.965-1.263-14.332-3.646l-2.867-1.564l-3.159,0.828l-6.482,1.699 l1.659-6.061l0.907-3.312l-1.718-2.974C11.38,50.437,9.997,45.255,10,39.986C10.007,23.453,23.464,10.002,39.997,10 c8.022,0.003,15.558,3.126,21.221,8.793C66.881,24.461,70,31.998,70,40.011C69.992,56.547,56.535,70,39.99,70z'/><path fill='#fff' d='M56.561,47.376c-0.9-0.449-5.321-2.626-6.143-2.924c-0.825-0.301-1.424-0.449-2.023,0.449 c-0.599,0.9-2.322,2.924-2.845,3.523c-0.524,0.599-1.048,0.674-1.948,0.226c-0.9-0.449-3.797-1.4-7.23-4.462 c-2.674-2.382-4.478-5.327-5.001-6.227c-0.524-0.9-0.057-1.385,0.394-1.834c0.403-0.403,0.9-1.051,1.349-1.575 c0.449-0.524,0.599-0.9,0.9-1.5c0.301-0.599,0.151-1.126-0.075-1.575c-0.226-0.449-2.023-4.875-2.773-6.673 c-0.729-1.752-1.472-1.515-2.023-1.542c-0.524-0.027-1.123-0.03-1.722-0.03c-0.599,0-1.575,0.226-2.397,1.126 c-0.822,0.9-3.147,3.074-3.147,7.498s3.222,8.699,3.671,9.298c0.449,0.599,6.338,9.678,15.36,13.571 c2.144,0.924,3.821,1.478,5.125,1.894c2.153,0.684,4.113,0.587,5.664,0.355c1.728-0.259,5.321-2.174,6.067-4.273 c0.75-2.099,0.75-3.899,0.524-4.273C58.06,48.051,57.461,47.825,56.561,47.376z'/></svg>
</sub>        
       <span style='margin-top:-10px; font-size:75px; text-shadow:0px 1px 6px white; color:green;' > Whatsapp</span>
        </h1>

        <center style='$orientation_display'>
        <h1>&ensp; </h1>
       </center>
        <h1 style='color:white; text-shadow:0px 2px 6px gray; font-size:72px;'>$orientation_empty_string_memories MEMORIES</h1>
        </center>
       <center style='$orientation_display'>
       <h1>&ensp; </h1>
      </center>
      <table style='width:100%;'>
      <tr>
      <td style='width:20%;'></td>
      <td style='width:35%;'>
      <div style='border-radius: 50%;'>
      <img src='$sender_image' 
       height='100' width='100'>
       </div>
      </td>
      <td style='width:35%;'><img src='$arrows' width='100'></td>
      <td style='width:35%;'>
      <div style='border-radius: 50%;'>
      <img src='$receiver_image' 
      height='100' width='100'>
      </div>
     </td>
      </tr>

      <tr>
      <td style='width:20%;'></td>
      <td style='width:35%;'>
      <div style='border-radius: 50%;color:white;'>
      $sender
       </div>
      </td>
      <td style='width:35%;'></td>
      <td style='width:35%;'>
      <div style='border-radius: 50%;color:white;'>
      $receiver
      </div>
     </td>
      </tr>
      
      </table>
</div>

       <div >
       <div class='conversation'>
        <div class='conversation-container'>
        ";
       $matches=[];
        foreach($lines as $line){
            list($dateAndName,$message)=explode(": ",$line,2);
            list($dateTimeAside,$name)=explode("] ",$dateAndName,2);
            $dateTimeAside=str_replace("[","",$dateTimeAside);
            $dateTimeAside=str_replace(",","",$dateTimeAside);
            $dateTimeAside=str_replace("/","-",$dateTimeAside);
            $date=new \DateTime();
            $date->setTimeStamp(strtotime(trim($dateTimeAside)));
            // $date='';
        //     $html.="
        //     <div class='before'></div>
        //     <div id='messageContainer' style='background:white; padding:5px; '>
        //     <h2 style='background:white; padding:5px; width:100%;'>
        //    $name
        //    </h2>
        $sent = preg_match_all(
          "/\b($sender)\b/i", 
          $name, 
          $matches
        );
        $received=preg_match_all(
          "/\b($receiver)\b/i", 
          $name, 
          $matches
        );
        $total_messages++;
        if(preg_match_all("~<attached:~",$message,$image_matches)){
          
          list($raw,$image)=explode(": ",$message);
          $image=str_replace(">","",trim($image));
         
          if(!empty($images_base64[$image]))
          {
            $total_images++;
            $total_messages--;
           $message="<img src='".$images_base64[$image]."' width='100%' height='300px'>";  
          }
        }
       
        if($sent){
          $message_type="sent";
        }
        if($received){
          $message_type="received";
        }
        if(preg_match("/Messages to this chat and calls are now secure/i",$message)){
          continue;
        }
      $time=$date->format("H:s:i A");
      $time=!preg_match("/00:00:00/i",$time)?$time:"";
      $image_match_style=!empty($image_matches)?'style="display:none;"':'';
      $html.="<div class='message $message_type' >
      $message
        <sub class='metadata')>$time</sub>
      </div>";
        //    ";
            // $html.=" 
            // <p>
            //   $message</p> </div>";
            // array_push($complete_message,["date"=>$date->format("m-d-Y H:i:s"),"name"=>$name,"message"=>$message]);
            // 20/06/20, 7:16:23 PM
            // dd(date("d-m-Y",strtotime($date)),$name);
        }

        $html.="</div>
        </div></body></html>";
        // echo $html;
        $pdf_name=time().rand(0,9999999);
        $name=storage_path("pdfs/$pdf_name.pdf");
        
    $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];
    $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];
 
    $mpdf=new \Mpdf\Mpdf([
      'orientation' => $orientation,
      'format' => $format,
        'fontDir' => array_merge($fontDirs, [
            storage_path()."\\fonts\\",
        ]),
        'fontdata' => $fontData + [
            'emoji' => [
                'R' => 'seguiemj.ttf',
                'I' => 'seguiemj.ttf',
                'useOTL' => 0xFF,
                'useKashida' => 75,
            ]
        ],
        'default_font' => 'emoji'
    ]);
    // dd($mpdf);
    $mpdf->writeHTML($html);
    $total_pages=$mpdf->page;
    $mpdf->Output($name);
        // $link=$pdf->setPaper('legal', 'potriate')->setWarnings(false)->save($name);
        return response()->json(["message"=>"Pdf Generated Successfully !",
              "pdfLink"=>$name,"total_pages"=>$total_pages,
              "total_messages"=>$total_messages,"total_images"=>$total_images]);
    }

    public function createOrder(Request $request){

          if($request->input("isOtpVerified")==false){
            return response()->json(["message"=>"OTP is not verified","status"=>500],500);
          }
          $userData=$request->input("userData");
          if(!\Auth::check()){
          $user=\App\User::create($userData);
          \Auth::login($user);
          }else{
            $user=\Auth::user();
          }
          if(!empty($userData["address"])){
             $address= \App\Models\Modules\Address::create([
                "full_name"=>$userData["address"]["full_name"],
                "mobile_number"=>$userData["address"]["mobile_number"],
                "address"=>$userData["address"]["address"],
                "city"=>$userData["address"]["city"],
                "state"=>$userData["address"]["state"],
                "landmark"=>$userData["address"]["landmark"],
                "user_id"=>$user->id
              ]);
          }
        
          $order=\App\Models\Modules\Order::create([
            "source"=>$request->input("source"),
              "cost_and_cover_details"=>json_encode($request->input("orderData")),
              "background_design"=>json_encode($request->input("colorData")),
              "user_id"=>$user->id,
              "address_id"=>$address->id,
              "net_cost"=>$request->input("net_cost"),
              "pdf_link"=>$request->input("pdf_link"),
              "grand_total"=>$request->input("grand_total"),
              "download_pdf"=>$request->input("download_pdf")
          ]);
       
          return response()->json(["message"=>"Order created Successfully !",
                  "status"=>200],200);
    }
}