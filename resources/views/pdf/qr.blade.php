<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style media="all">
		    @page { margin: 0.2in 0.2in 0.2in 0.2in;}
		</style>
    </head>
    <body>

        <h6 class="text-center">L & C Courier Inc</h6>
        <p class="text-center" style="font-size: 9px;">83 01 NW ST</p>
        <p class="text-center" style="margin-top: -20px; font-size: 9px;">Miami, FL 33166</p>
        <p class="text-center" style="margin-top: -20px; font-size: 9px;">UNITED STATES OF AMERICA</p>

        <hr>

        <p style="margin-top: -20px; font-size: 12px;">SHIPPER</p>
        <p style="margin-top: -10px; font-size: 14px">L & C Courier Inc</p>

        <hr style="margin-top: -10px;">

        <p style="margin-top: -20px; font-size: 12px;">CONSIGNEE</p>
        <p style="margin-top: -10px; font-size: 14px;">{{ $shipping->recipient->name }}</p>

        <hr>

        <p style="margin-top: -20px; font-size: 11px;"><span style="margin-right: 20px;"><strong>RECEIPT #</strong> {{ $shipping->warehouse_number }}</span>    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<span><strong>DATE:</strong> {{ $shipping->shipped_at->format("m/d/Y") }}</span></p>  
        <h1 class="text-center" style="margin-top: -10px; font-weight: bolder; font-size: 65px;">{{ substr($shipping->warehouse_number, 3, strlen($shipping->warehouse_number)) }}</h1>      

        <table style="width: 100%; ">
            <tr style="line-height: 7px !important;">
                <td style="border-top: 1px solid; border-bottom: 1px solid; border-right: 1px solid; padding: 0 !important" colspan="3">
                    <p style="font-size: 11px; margin:0 !important; padding:0!important; margin-left: 10px; margin-top: 5px;">LOCATION</p>
                    <p style="font-size: 11px; margin:0 !important; padding:0!important; margin-left: 10px; margin-top: 8px;"><span style="color: #FFF;">hey</span></p>
                </td>
                <td style="border-top: 1px solid; border-bottom: 1px solid; border-left: 1px solid; padding: 0 !important" colspan="3">
                    <p style="font-size: 11px; margin:0 !important; padding:0!important; margin-left: 10px; margin-top: 5px;">PCS</p>
                    <p style="font-size: 13px; margin:0 !important; padding:0!important; margin-left: 10px; margin-top: 8px;">@if($shipping->pieces != null){{ $shipping->pieces }} of {{ $shipping->pieces }} @else <span style="color: #FFF;">hey</span> @endif</p>
                </td>
            </tr>
            <tr style="line-height: 9px !important;">
                <td style="border-top: 1px solid; border-bottom: 1px solid; border-right: 1px solid; padding: 0 !important" colspan="3">
                    <p style="font-size: 11px; margin:0 !important; padding:0!important; margin-left: 10px; margin-top: 5px;">DESTINATION</p>
                    <p style="font-size: 13px; margin:0 !important; padding:0!important; margin-left: 10px; margin-top: 8px;">LIMA</p>
                </td>
                <td style="border-top: 1px solid; border-bottom: 1px solid; border-left: 1px solid; padding: 0 !important;" colspan="3">
                    <p style="font-size: 11px; margin:0 !important; padding:0!important; margin-left: 10px; margin-top: 5px;">PACKAGE TYPE</p>
                    <p style="font-size: 13px; margin:0 !important; padding:0!important; margin-left: 10px; margin-top: 8px;">{{ $shipping->box->name }}</p>
                </td>
            </tr>
            <tr style="line-height: 9px !important;">
                <td style="border-top: 1px solid; border-bottom: 1px solid; border-right: 1px solid; padding: 0 !important" colspan="2">
                    <p style="font-size: 11px; margin:0 !important; padding:0!important; margin-left: 10px; margin-top: 5px;">UNIT WEIGHT</p>
                    <p style="font-size: 13px; margin:0 !important; padding:0!important; margin-left: 10px; margin-top: 8px;">@if($shipping->weight != null){{ $shipping->weight }} KG @else <span style="color: #fff;">hey</span> @endif</p>
                </td>
                <td style="border-top: 1px solid; border-bottom: 1px solid; border-right: 1px solid; padding: 0 !important" colspan="2">
                    <p style="font-size: 11px; margin:0 !important; padding:0!important; margin-left: 10px; margin-top: 5px;">TOTAL WEIGHT</p>
                    <p style="font-size: 13px; margin:0 !important; padding:0!important; margin-left: 10px; margin-top: 8px;">@if($shipping->weight != null){{ $shipping->weight }} KG @else <span style="color: #fff;">hey</span>  @endif</p>
                </td>
                <td style="border-top: 1px solid; border-bottom: 1px solid;padding: 0 !important" colspan="2">
                    <p style="font-size: 11px; margin:0 !important; padding:0!important; margin-left: 10px; margin-top: 5px;">DIMENSIONS</p>
                    <p style="font-size: 13px; margin:0 !important; padding:0!important; margin-left: 10px; margin-top: 8px;">@if($shipping->width != null){{ $shipping->width }} @else 0 @endif x @if($shipping->height != null){{ $shipping->height }} @else 0 @endif x @if($shipping->length != null){{ $shipping->length }} @else 0 @endif in</p>
                </td>
            </tr>
            <tr style="line-height: 9px !important;">
                <td style="border-top: 1px solid; border-bottom: 1px solid; padding: 0 !important" colspan="6">
                    <p style="font-size: 11px; margin:0 !important; padding:0!important; margin-left: 10px; margin-top: 5px;">Description</p>
                    <p style="font-size: 13px; margin:0 !important; padding:0!important; margin-left: 10px; margin-top: 8px;">
                    {{ substr("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.", 0, 90) }}
                    </p>
                </td>
            </tr>

            <p class="text-center">
                <img id='barcode' 
                src="{{ $data }}" 
                alt="" 
                title="HELLO" 
                width="70" 
                height="70" />
            </p>


            
        </table></body></html>

