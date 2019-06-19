<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
class EmailController extends Controller
{
    public function enviarCorreo($asunto, $contenido, $adjunto)
    {
        $asunto = $request->asunto;
        $contenido = $request->contenido;
        $adjunto = $request->file('adjunto');
 
        Mail::send('welcome', ['contenido' => $contenido], function ($mail) use ($asunto, $adjunto) {
            $mail->from('final@software.com', 'Bot de correos');
            $mail->to('user@mail.com');
            $mail->subject($asunto);
            $mail->attach($adjunto);
        });
        /** Respondemos con status OK */
        return response()->json(['status' => 200, 'message' => 'Envío exitoso']);
    }
}