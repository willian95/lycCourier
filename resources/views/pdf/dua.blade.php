<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style media="all">
		    @page { margin: 0.2in 0.2in 0.2in 0.2in;}
		</style>
    </head>
    <body>

        <h1 style="margin-top: -10px; font-weight: bolder; font-size: 20px;">HAWB: {{ $dua->hawb }}</h1>
        <h1 style="margin-top: -10px; font-weight: bolder; font-size: 20px;">ESSER: {{ $dua->esser }}</h1>   
        <h1 style="margin-top: -10px; font-weight: bolder; font-size: 20px;">CLIENTE: {{ $dua->client }}</h1>  
        <h1 style="margin-top: -10px; font-weight: bolder; font-size: 20px;">AWB: {{ $dua->awb }}</h1>   
        <h1 style="margin-top: -10px; font-weight: bolder; font-size: 20px;">MANIFIESTO: {{ $dua->manifest }}</h1> 
        <h1 style="margin-top: -10px; font-weight: bolder; font-size: 20px;">VOLANTE: {{ $dua->volante }}</h1>      
        <h1 style="margin-top: -10px; font-weight: bolder; font-size: 20px;">{!! DNS1D::getBarcodeHTML($dua->dua, 'CODABAR') !!}</h1>
        

        <p class="text-center">
            <img id='barcode' 
            src="{{ $data }}" 
            alt="" 
            title="HELLO" 
            width="50" 
            height="50" />
        </p>

        <h1 style="font-weight: bolder; font-size: 20px; text-align: center;">{{ $dua->dua }}</h1>
        <h1 style="margin-top: -10px; font-weight: bolder; font-size: 20px;">PESO: {{ $dua->weight }} KG &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; BULTOS: {{ $dua->pieces }}</h1>  


    </body></html>

        

