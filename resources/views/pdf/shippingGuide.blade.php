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

            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td colspan="2">LYC{{ str_pad($shipping->shippingGuide->guide, 9, "0", STR_PAD_LEFT) }}</td>
                    <td colspan="2" style="text-align: right;">LYC COURIER INC</td>
                </tr>
                <tr style="width: 100%; border: 1px solid #000; border-bottom: 1px solid #000;">
                    <td colspan="4" style="border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; border-collapse: collapse; border-bottom: 1px solid #000;">
                        <h1 style="text-align:center; font-size: 75px;">LYC{{ str_pad($shipping->shippingGuide->guide, 9, "0", STR_PAD_LEFT) }}</h1>
                    </td>
                </tr>
                <tr style="border-collapse: collapse;">
                    <td colspan="4" style="text-align:center; border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; border-collapse: collapse; padding-top: 10px; padding-bottom: 10px;">

                        {{--<p style="text-align: center;">Codigo de barras</p>--}}
                        @php

                            $description = str_replace(" ", "-", $shipping->shipping->description);
                            $description = str_replace(",", "", $description);

                            $guideNumber = "LYC".str_pad($shipping->shippingGuide->guide, 9, "0", STR_PAD_LEFT);
                        @endphp
                        <p style="text-align: center;">
                            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($guideNumber,'C39') }}" width="400" height="100"/>
                        </p>
                        <p style="text-align: center;">
                            {{ "LYC".str_pad($shipping->shippingGuide->guide, 9, "0", STR_PAD_LEFT)."-".$description }}
                        </p>
                    
                    </td>
                </tr>
                <tr style="border-collapse: collapse;">
                    <td colspan="4" style="border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; height: 30px; border-collapse: collapse;"></td>
                </tr>
                <tr>
                    <td style="padding-top: 30px; padding-bottom: 30px;border-top: 1px solid #000; border-left: 1px solid #000;"><strong>FROM: MIA</strong></td>
                    <td style="padding-top: 30px; padding-bottom: 30px;border-top: 1px solid #000;" colspan="2"><strong>TO: LIM</strong></td>
                    <td style="padding-top: 30px; padding-bottom: 30px;text-align:right; border-top: 1px solid #000; border-right: 1px solid #000;"><strong>DATE: {{$shipping->created_at->format('d/m/Y')}}</strong></td>
                </tr>
                <tr>
                    <td colspan="4" style="border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; height: 30px;"></td>
                </tr>
                <tr style="border-collapse: collapse;">
                    <td colspan="4" style="border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; padding-top: 10px; padding-bottom: 10px;">

                        <h3>SHIPPER              ITA EXPORT SALES INC</h3>
                        <h3>ADDRESS              10331SW 138th Th Court MIAMI, Fl 33186</h3>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; height: 30px;"><p><strong>DESCRIPTION:</strong> {{ $shipping->shipping->description }}</p></td>
                </tr>
                <tr>
                    <td style="padding-top: 30px; padding-bottom: 30px; border-top: 1px solid #000; border-left: 1px solid #000;"><strong>PIECES</strong></td>
                    <td style="padding-top: 30px; padding-bottom: 30px; border-top: 1px solid #000;"><strong>{{ $trackingCount }}/{{ $trackingAmount }}</strong></td>
                    <td style="padding-top: 30px; padding-bottom: 30px; border-top: 1px solid #000;"><strong>WEIGHT</strong></td>
                    <td style="padding-top: 30px; padding-bottom: 30px; text-align:right; border-top: 1px solid #000; border-right: 1px solid #000;"><strong>{{ $shipping->shipping->weight }}KG</strong></td>
                </tr>
                <tr>
                    <td colspan="4" style="border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000; height: 30px;"></td>
                </tr>
                <tr style="border-collapse: collapse;">
                    <td colspan="4" style="border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">

                        <h3>CONSIGNEE</h3>
                        <h3>{{ $shipping->shipping->client->name }} {{ $shipping->shipping->client->lastname }}</h3>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 30px; padding-bottom: 30px; border-top: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000;"><strong>ADDRESS</strong></td>
                    <td style="padding-top: 30px; padding-bottom: 30px; border-top: 1px solid #000; border-bottom: 1px solid #000;"><strong>LIMA</strong></td>
                    <td style="padding-top: 30px; padding-bottom: 30px; border-top: 1px solid #000; border-bottom: 1px solid #000;"><strong>COUNTRY</strong></td>
                    <td style="padding-top: 30px; padding-bottom: 30px; text-align:right; border-top: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000;"><strong>PERU</strong></td>
                </tr>
                
            </table>
            
            @if($loop->index + 1 < count($shippingGuideShippings))
                <div style="page-break-after: always;"></div>
            @endif
        @endforeach

    </body>
</html>

