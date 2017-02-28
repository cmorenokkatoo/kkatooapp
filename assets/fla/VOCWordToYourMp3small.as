package
{
   	import flash.display.*;
   	import flash.events.*;
	import flash.text.*;
	import flash.net.*;	
	import flash.media.*;
	import flash.utils.*;
	import fl.controls.Button;
	import flash.external.ExternalInterface;

	import org.as3wavsound.WavSound;
	import org.bytearray.micrecorder.MicRecorder;
	import org.bytearray.micrecorder.encoder.WaveEncoder;
	import org.bytearray.micrecorder.events.RecordingEvent;	

	import com.noteflight.standingwave3.elements.*;
	import com.noteflight.standingwave3.filters.*;
	import com.noteflight.standingwave3.formats.*;
	import com.noteflight.standingwave3.generators.*;
	import com.noteflight.standingwave3.modulation.*;
	import com.noteflight.standingwave3.output.*;
	import com.noteflight.standingwave3.performance.*;
	import com.noteflight.standingwave3.sources.*;
	import com.noteflight.standingwave3.utils.*;	
	
	import com.greensock.*;
	
	import fr.kikko.lab.ShineMP3Encoder;
	
	public class VOCWordToYourMp3small extends MovieClip {
		// mic vars
		public var recorder:MicRecorder = new MicRecorder(new WaveEncoder());	
		private var recording:Boolean = false;		
		// SW3 vars
		public var player:AudioPlayer = new AudioPlayer()
		//public var sequence:ListPerformance = new ListPerformance()		
		// mp3 vars
		private var mp3Encoder:ShineMP3Encoder;			
		private var myWavData:ByteArray = new ByteArray()
		private var myWavFile:ByteArray = new ByteArray()
		// UI vars
		//public var wavbtn:MovieClip;
		// Sine vars
		private var speedX:Number = 1;
		private var speedAngle:Number = 0.3;
		private var amplitude:Number = 45;
		private var angle:Number = 0;
		private var xpos:Number = 0;
		private var ypos:Number = 0;		
		private var centerY:Number = 350;
		
		public var jtlogo:MovieClip;
		
		/************************************************************/
		// MÉTODO CONSTRUCTOR PARA INICIALIZAR TODO EL PROCESO.
		/************************************************************/
		public function VOCWordToYourMp3small()
		{
			//AGREGO LOS LISTENERS PARA EL BOTON GRABAR
			btnrec.addEventListener(MouseEvent.CLICK, onBtnClick)
			btnrec.addEventListener(MouseEvent.ROLL_OVER, onBtnRoll)
			btnrec.addEventListener(MouseEvent.ROLL_OUT, onBtnRoll)			
			btnrec.buttonMode = true;
			
			statustxt.text = "Presiona el botón rojo para empezar a grabar"
			statustxt2.text = "Debes 'PERMITIR' que el sistema tenga acceso a tu micrófono"
			
			//AGREGO LOS LISTENES AL OBJETO RECORDER
			recorder.addEventListener(RecordingEvent.RECORDING, onRecording)
			recorder.addEventListener(Event.COMPLETE, onRecordComplete)
			//Y AL OBJETO PLAYER
			player.addEventListener(Event.COMPLETE, onPlayComplete)
			
			//CONFIGURO EL BOTON "CODIFICAR MP3" Y LO OCULTO
			this.wavbtn.addEventListener(MouseEvent.CLICK, onCodifyClick)
			this.wavbtn.buttonMode = true;
			this.wavbtn.visible = false;
			
			//CONFIGURO EL BOTON "GUARDAR AUDIO" Y LO OCULTO
			this.wavbtn2.addEventListener(MouseEvent.CLICK, onWavClick)
			this.wavbtn2.buttonMode = true;
			this.wavbtn2.visible = false;
			trace (this.wavbtn2.visible)
			
			//CONFIGURO EL CAMPO DE TEXTO DE "NOMBRE DE ARCHIVO" Y LO OCULTO
			this.nombre_txt.visible = false;
			this.nombre_txt.addEventListener(MouseEvent.CLICK, onTxtClick)
			this.fondo_txt.visible = false;
			
			//this.wavbtn.alpha = .5;
			
			//CONFIGURO EL BOTON PLAY Y LO COLOCO INACTIVO
			//btnplay.addEventListener(MouseEvent.CLICK, playRec);
			btnplay.buttonMode = false;
			btnplay.visible = true;
			btnplay.alpha = .5;
			
			//CONFIGURO EL CONTROL DEL VOLUMEN Y OCULTO EL TEXTO
			soundMeter.scaleX = 0
			vc_text.visible = false;
			//initSineDrawer();
			
	
		}

		/**
		* get flashvar
		*/
		
		function get_flashvar(flashvar:String = ""):String{
			var paramObj:Object = LoaderInfo(this.root.loaderInfo).parameters;
			if(paramObj[flashvar]){
				return String(unescape(paramObj[flashvar]));
			}else{
				return "";
			}
		}
		/*********************************************************************/
		// METODO PARA LIMPIAR EL CAMPO DE TEXTO UNA VEZ SE HACE CLIC EN ÉL
		/*********************************************************************/
		public function onTxtClick(e:MouseEvent) 
		{
			if (nombre_txt.text =="NOMBRE ARCHIVO") 
			{
				nombre_txt.text = "";
			}
		}
		
		
		/*********************************************************************/
		// METODOS PARA RESPONDER AL LOS EVENTOS DEL BOTON GRABAR
		/*********************************************************************/
		public function onBtnClick(e:MouseEvent)		
		{
			startRecording()
		}
		
		public function onBtnRoll(e:MouseEvent)			
		{								
		}		
		
		
		/*************************************************************************************/
		// METODO QUE DIBUJA UNA LINEA (PARA EL MEDIDOR DE VOLUMEN DINAMICO QUE NO SE USA)
		/*************************************************************************************/
		public function initSineDrawer()
		{
			// Sine Drawing
			graphics.lineStyle(1.5, 0x000000);
			graphics.moveTo(0, 155);			
		}
		
		
		/*********************************************************************/
		// METODO PARA EMPEZAR A GRABAR EL AUDIO
		/*********************************************************************/
		public function startRecording()
		{
			//DESAPAREZCO LOS BOTONES Y EL CAMPO DE TEXTO (POR SI ESTÁN PRESENTES)
			wavbtn.visible = false;
			wavbtn2.visible = false;
			nombre_txt.visible = false;
			fondo_txt.visible = false;
			//DESVANEZCO EL BOTON PLAY
			btnplay.alpha = .1;
			//wavbtn.alpha = .5;
			//wavbtn.gotoAndStop(1);
			
			
			//MUESTRO EL CONTROL DE VOLUMEN Y EL MC DE LA BARRITA DE VOLUMEN
			vc_text.visible = true;
			soundMeter.visible = true;
			
			//CREO UN HALO ALREDEDOR DEL BOTON REC PARA QUE EL USUARIO SEPA QUE SE ESTÁ GRABANDO
			if (!recording) 
			{
				TweenMax.to(btnrec, .3, {glowFilter:{color:0xFCAF17, alpha:1, blurX:30, blurY:30}} )
				recorder.record() 
				
			} else if (recording) {
				recorder.stop();
				recording = false;
				TweenMax.to(btnrec, .3, {glowFilter:{color:0x999999, alpha:0, blurX:10, blurY:10}} )				
			}
		}


		/*********************************************************************/
		// METODO QUE RESPONDE AL LISTENER DEL OBJETO RECORDER
		/*********************************************************************/
		public function onRecording(e:RecordingEvent)
		{			
			statustxt.text = "Grabando... puedes empezar a hablar."	
			var al:Number = recorder.microphone.activityLevel;
			TweenMax.to(soundMeter, .1, {scaleX:al * .01/*, onUpdate:onActivitylevelUpdate, onUpdateParams:[al]*/});
			if (!recording) recording = true;
			btnrec.gotoAndStop(2);
		}		


		/*********************************************************************/
		// METODO PARA DIBUJAR EL MEDIDOR DE VOLUMEN DINÁMICO (NO SE USA)
		/*********************************************************************/
		public function onActivitylevelUpdate(al)
		{
			//statustxt.text = _activityLevel
			// draw a cool sine wave!
			xpos += speedX;
			ypos = centerY + Math.sin(angle) * amplitude * ((al > 20)? al / 100 : 1)
			angle += speedAngle;
			graphics.lineTo(xpos,ypos)
		}
		
		
		/**********************************************************************************/
		// METODO QUE RESPONDE AL LISTENER DEL OBJETO RECORDER (AL TERMINAR LA GRABACIÓN)
		/**********************************************************************************/
		private function onRecordComplete(e:Event):void
		{
			//REDUZCO A CERO EL TAMAÑO DEL MEDIDOR DE VOLUMEN Y OCULTO TODO ÉSTE
			soundMeter.scaleX = 0
			soundMeter.visible = false;
			vc_text.visible = false;
			
			//DETENGO LA VARIABLE DE GRABACIÓN Y CAMBIO TEXTO
			recording = false;
			statustxt.text = "Grabación completa"
			btnrec.gotoAndStop(1);
						
			var src = WaveFile.createSample(recorder.output) // this is fine
			
			// I think im not clearing out the old audio properly here somehow...
			var sequence = new ListPerformance()
			sequence.addSourceAt(0, src)
			var ap:AudioPerformer = new AudioPerformer(sequence, new AudioDescriptor())
			//player.play(ap)
			
			renderWav(ap, false)
			
			// save to wav?
			//new FileReference().save (recorder.output, "FlashMicrophoneTest.wav")
		}
		
		/*********************************************************************/
		// METODO QUE RESPONDE AL LISTENER DEL BOTON PLAY
		/*********************************************************************/
		private function playRec(e:MouseEvent):void
		{
			var tts:WavSound = new WavSound(myWavFile); 
			tts.addEventListener(Event.OPEN, onPlayComplete);
            tts.play(); 
			//OCULTO EL BOTON PARA EVITAR QUE LO PRESIONEN VARIAS VECES
			btnplay.buttonMode = false;
			//btnplay.visible = false;
			btnplay.alpha = 0.1;
			btnplay.removeEventListener(MouseEvent.CLICK, playRec)
			espera();
			
		}
		
		private function espera(){
			var segundos:Number = 5;
			var espera:Function = function () { 
				btnplay.buttonMode = true;
				btnplay.alpha = 1;
				btnplay.addEventListener(MouseEvent.CLICK, playRec);

				clearInterval(a);
			};
			var a = setInterval(espera, segundos*1000);
		}
		
		
		private function renderWav(src, convertToMp3 = false)
		{
			var innerTimer = new Timer(10,0)
			var framesPerChunk:uint = 8192;
			
			innerTimer.addEventListener(TimerEvent.TIMER, handleRenderTimer)
			innerTimer.start()
			
			//esto lo pongo aquí, yo todo bonito, para que el wav se renueve y no se acumule con la grabación anterior
			myWavData = new ByteArray()
			myWavFile = new ByteArray()
			
			function handleRenderTimer(e:TimerEvent)
			{
				src.getSample(framesPerChunk).writeWavBytes(myWavData)
				
				var m = Math.min(src.frameCount, src.position + framesPerChunk)
				var n = Math.max(0, m - src.position)
				
				if (n == 0)
				{
					if (src.position > 0) finishRender() else trace("Cancelar renderización")
					
				} else {
					statustxt.text = "Renderizando audio: "+ Math.floor(src.position * 100 / src.frameCount) + "%";
				}
			}				
			function finishRender()
			{
				innerTimer.stop()
				statustxt.text = "Renderización finalizada";
				statustxt2.text = "Prueba el audio (con el botón Play) o presiona 'CODIFICAR MP3'";
				WaveFile.writeBytesToWavFile(myWavFile, myWavData, 44100, 2, 16)
				//new FileReference().save (myWavFile, "FlashMicrophoneTest.wav")
				

				if (!convertToMp3)
				{
					wavbtn.visible = true;
					wavbtn.buttonMode = true;
					wavbtn.alpha = 1;

					btnplay.buttonMode = true;
					btnplay.visible = true;
					btnplay.alpha = 1;
					btnplay.addEventListener(MouseEvent.CLICK, playRec);
				} else {
					makeIntoMp3(myWavFile)
				}
			}				
		}
		
		private function makeIntoMp3(wav)
		{
			wav.position = 0
			mp3Encoder = new ShineMP3Encoder(wav);
			mp3Encoder.addEventListener(Event.COMPLETE, mp3EncodeComplete);
			mp3Encoder.addEventListener(ProgressEvent.PROGRESS, mp3EncodeProgress);
			//mp3Encoder.addEventListener(ErrorEvent.ERROR, mp3EncodeError);
			mp3Encoder.start();	
			
			function mp3EncodeProgress(e:ProgressEvent) : void 
			{
				statustxt.text = "Codificando a MP3: " + e.bytesLoaded + "%"
			}
			
			function mp3EncodeComplete(e: Event) : void 
			{
				//pongo este textico así pa que esta vuelta suba el archivo
				//wavbtn.texto_txt.text="Subir";
				wavbtn.visible = false;
				wavbtn.buttonMode = false;
				
				wavbtn2.visible = true;
				wavbtn2.alpha = 1;
				
				//wavbtn.x += 136;
				nombre_txt.visible = true;
				fondo_txt.visible = true;
				
				
				/*btnplay.visible = true;
				btnplay.alpha = 1;*/
				
				statustxt.text = "Codificación a MP3 completa\n";
				statustxt2.text = "Colócale un nombre (en el campo inferior) y presiona 'GUARDAR AUDIO', para subirlo a internet";
			}					
		}
		
		
		private function onCodifyClick(e:MouseEvent)
		{ 
			wavbtn2.visible = false;
			//wavbtn2.alpha = .7;
			makeIntoMp3(myWavFile);
		}
		
		private function onWavClick(e:MouseEvent)
		{
			//if(wavbtn.currentFrame==1)
			//{
			//	wavbtn.visible = false;
			//	wavbtn.alpha = .7;
			//	makeIntoMp3(myWavFile);
			//}else{
				statustxt.text = "Subiendo el archivo...";
				
				// WRITE ID3 TAGS
				var sba:ByteArray = mp3Encoder.mp3Data;
				sba.position =  sba.length - 128
				sba.writeMultiByte("TAG", "iso-8859-1");
				sba.writeMultiByte("Audio desde kkatoo      "+String.fromCharCode(0), "iso-8859-1");	// Title
				sba.writeMultiByte("kka.to         " + String.fromCharCode(0), "iso-8859-1");// comments
				sba.writeByte(57);
				
				//para guardar en un archivo
				//new FileReference().save(sba, "FlashMicrophoneTest.mp3")
				
				var urlRequest:URLRequest = new URLRequest(get_flashvar("saveurl"));//"http://kka.to/kkatoo/apps/add_audio_record");
				urlRequest.method = URLRequestMethod.POST;
				urlRequest.contentType='multipart/form-data; boundary=' + UploadPostHelper.getBoundary();
				urlRequest.data = UploadPostHelper.getPostData(nombre_txt.text + ".mp3",sba, {nombre:nombre_txt.text});
				//urlRequest.data = UploadPostHelper.getPostData("audio.mp3",sba);
				urlRequest.requestHeaders.push( new URLRequestHeader( 'Cache-Control', 'no-cache' ) );
				
				var urlLoader:URLLoader = new URLLoader();
				urlLoader.dataFormat = URLLoaderDataFormat.BINARY;
				urlLoader.addEventListener(Event.COMPLETE, onUploadComplete);
				//urlLoader.addEventListener(IOErrorEvent.IO_ERROR, onError);
				//urlLoader.addEventListener(SecurityErrorEvent.SECURITY_ERROR, onError);
				urlLoader.load(urlRequest);
			//}
		}
		
		private function onPlayComplete(e:Event)
		{
			btnplay.visible = true;
			btnplay.buttonMode = true;
			trace ("Terminó de reproducirse");			
		}/**/
		
		function onUploadComplete(event:Event): void
		{
			//statustxt.text = event.target.data+"";
			var variables:URLVariables = new URLVariables(event.target.data);
			
			trace(variables.cod);
			trace(variables.messa);
			if(variables.cod=="1")
			{
				ExternalInterface.call("redireccionar", variables.messa);
			}
			statustxt.text = variables.messa;
		}
	}
}