<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style media="all">
		    @page { margin: 0.2in 0.2in 0.2in 0.2in;}
		</style>
    </head>
    <body>
        
        @php
            $tracking = "";
            $trackingCount = 1;
            $trackingAmount = 0;
        @endphp

        @foreach($shippingGuideShippings as $shipping)

            @php
                $trackingAmount = 0;
                foreach($shippingGuideShippings as $ship){
                    if($ship->shipping->tracking == $shipping->shipping->tracking){
                        $trackingAmount++;
                    }
                }

                if($shipping->shipping->tracking == $tracking){
                    $trackingCount++;
                }else{

                    $trackingCount = 1;

                }

                $tracking = $shipping->shipping->tracking;

            @endphp

            <table style="width: 100%;">
                <tr style="width: 100%; border: 1px solid #000; border-collapse: collapse;">
                    <td colspan="4" style="border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
                        <h1 style="text-align:center;">CAH000002764</h1>
                    </td>
                </tr>
                <tr style="border-collapse: collapse;">
                    <td colspan="4" style="border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">

                        <p style="text-align: center;">Codigo de barras</p>
                    
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; height: 30px;"></td>
                </tr>
                <tr>
                    <td style="border-top: 1px solid #000; border-left: 1px solid #000;"><strong>FROM: MIA</strong></td>
                    <td style="border-top: 1px solid #000;" colspan="2"><strong>TO: LIM</strong></td>
                    <td style="text-align:right; border-top: 1px solid #000; border-right: 1px solid #000;"><strong>DATE: {{$shipping->created_at->format('d/m/Y')}}</strong></td>
                </tr>
                <tr>
                    <td colspan="4" style="border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; height: 30px;"></td>
                </tr>
                <tr style="border-collapse: collapse;">
                    <td colspan="4" style="border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">

                        <h3>SHIPPER              ITA EXPORT SALES INC</h3>
                        <h3>ADDRESS              10331SW 138th Th Court MIAMI, Fl 33186</h3>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; height: 30px;"><p>DESCRIPTION: {{ $shipping->shipping->description }}</p></td>
                </tr>
                <tr>
                    <td><strong>PIECES</strong></td>
                    <td><strong>{{ $trackingCount }}/{{ $trackingAmount }}</strong></td>
                    <td><strong>WEIGHT:</strong></td>
                    <td><strong>{{ $shipping->shipping->weight }}KG</strong></td>
                </tr>
                
            </table>
            
            @if($loop->index + 1 < count($shippingGuideShippings))
                <div style="page-break-after: always;"></div>
            @endif
        @endforeach

    </body>
</html>

