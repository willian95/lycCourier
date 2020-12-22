<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style media="all">
		    @page { margin: 0.5in 0.5in 0.5in 0.5in;}
		</style>
    </head>
    <body>

        <table style="width: 100%;">
            <tr>
                <td>
                    <p><strong>L & C Courier</strong></p>
                    <p style="font-size: 12px; line-height: 1px;">8301 NW 66 ST</p>
                    <p style="font-size: 12px; line-height: 1px;">Miami, FL 33166</p>
                    <p style="font-size: 12px; line-height: 1px;">UNITED STATES OF AMERICA</p>
                    <p style="font-size: 12px; line-height: 1px;">josecahuas@hotmail.com</p>
                    <p style="font-size: 9px; line-height: 1px;">Printed on: {{ Carbon\Carbon::now()->format('m/d/Y H:i:s A') }}</p>
                    <p style="font-size: 9px; line-height: 1px;">Printed by: {{ \Auth::user()->email }}</p>
                </td>
                <td>
                    <p style="text-align: right;">WAREHOUSE RECEIPT</p>
                    <p class="text-right">
                        <img id='barcode' 
                        src="{{ $data }}" 
                        alt="" 
                        title="HELLO" 
                        width="70" 
                        height="70" />
                    </p>
                </td>
            </tr>
        </table>

        <table style="width: 100%;">
            <tbody>
                <tr>
                    <td style="border: 1px solid #000;" colspan="4">SHIPPER</td>
                    <td  colspan="4"></td>
                </tr>
                <tr>
                    <td style="border: 1px solid #000; padding-left: 15px;" colspan="4">
                        <p style="font-size: 12px; line-height: 1px; margin-top: 15px;">L & C Courier</p>
                        <p style="font-size: 12px; line-height: 1px;">8301 NW 66 ST</p>
                        <p style="font-size: 12px; line-height: 1px;">Miami, FL 33166</p>
                        <p style="font-size: 12px; line-height: 1px;">UNITED STATES OF AMERICA</p>
                        <p style="font-size: 12px; line-height: 1px;">4077859162</p>
                        <p style="font-size: 12px; line-height: 1px;">josecahuas@hotmail.com</p>
                    </td>
                    <td colspan="4" style="padding-left: 20px;">

                        <table style="width: 100%">
                            <tr>
                                <td style="font-size: 10px;"><div style="width: 10px; height: 10px; border: 1px solid #000; position: absolute; margin-left: -15px;"></div> <span>COMMERCIAL INVOICE</span></td>
                                <td style="font-size: 10px;"><div style="width: 10px; height: 10px; border: 1px solid #000; position: absolute; margin-left: -15px;"></div>OVERSIZE</td>
                            </tr>
                            <tr>
                                <td style="font-size: 10px;"><div style="width: 10px; height: 10px; border: 1px solid #000; position: absolute; margin-left: -15px;"></div> <span>PACKAGING LIST</span></td>
                                <td style="font-size: 10px;"><div style="width: 10px; height: 10px; border: 1px solid #000; position: absolute; margin-left: -15px;"></div>OVERWEIGHT</td>
                            </tr>
                            <tr>
                                <td style="font-size: 10px;"><div style="width: 10px; height: 10px; border: 1px solid #000; position: absolute; margin-left: -15px;"></div> <span>HAZARDOUS MATERIAL</span></td>
                                <td style="font-size: 10px;"><div style="width: 10px; height: 10px; border: 1px solid #000; position: absolute; margin-left: -15px;"></div>DISCREPANCY</td>
                            </tr>
                            <tr>
                                <td style="font-size: 10px;"><div style="width: 10px; height: 10px; border: 1px solid #000; position: absolute; margin-left: -15px;"></div> <span>BONDED</span></td>
                                <td style="font-size: 10px;"><div style="width: 10px; height: 10px; border: 1px solid #000; position: absolute; margin-left: -15px;"></div>FRAGILE</td>
                            </tr>
                            <tr>
                                <td style="font-size: 10px;"><div style="width: 10px; height: 10px; border: 1px solid #000; position: absolute; margin-left: -15px;"></div> <span>LITHIUM BATTERIES</span></td>
                                <td style="font-size: 10px;"><div style="width: 10px; height: 10px; border: 1px solid #000; position: absolute; margin-left: -15px;"></div>DAMAGED</td>
                            </tr>
                        </table>
                        {{--<div style="width: 10px; height: 10px; border: 1px solid #000;"></div><p style="font-size: 10px;">COMMERCIAL INVOICE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div style="width: 10px; height: 10px; border: 1px solid #000;"></div>OVERSIZE</p>
                        <p style="font-size: 10px;">PACKAGING LIST&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OVERWEIGHT</p>
                        <p style="font-size: 10px;">HAZARDOUS MATERIAL        DISCREPANCY</p>
                        <p style="font-size: 10px;">BONDED                    FRAGILE</p>
                        <p style="font-size: 10px;">LITHIUM BATTERIES         DAMAGED</p>--}}
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid #000;" colspan="4">
                        CONSIGNEE
                    </td>
                    <td style="border: 1px solid #000;" colspan="4">
                        ADDITIONAL INFORMATION
                    </td>
                </tr>
                <tr>
                    @if($shipping->recipient)
                    <td style="border: 1px solid #000;" colspan="4">
                        <p>{{ $shipping->recipient->name }}</p>
                        <p>{{ $shipping->recipient->email }}</p>
                    </td>
                    @elseif($shipping->client)
                    <td style="border: 1px solid #000;" colspan="4">
                        <p>{{ $shipping->client->name }}</p>
                        <p>{{ $shipping->client->email }}</p>
                    </td>
                    @endif
                    <td style="border: 1px solid #000;" colspan="4">
                        <p style="font-size: 10px;"><span style="margin-right: 25px;">DATE IN: {{ $shipping->created_at->format('m/d/Y') }}
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span>TIME IN: {{ $shipping->created_at->format('H:i:s A') }}</span></p>
                        <p style="font-size: 10px;"><span style="margin-right: 25px;">DIVISON: 
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span>MODE:</span></p>

                        <p style="font-size: 10px;"><span style="margin-right: 25px;">CARRIER: 
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span>PRO#:</span></p>

                        <p style="font-size: 10px;"><span style="margin-right: 25px;">ORIGIN: MIAMI
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span>DEST: LIMA</span></p>
                        <p style="font-size: 10px;"><span style="margin-right: 25px;">TRACKING: {{ $shipping->tracking }}</p>
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid #000; font-size: 10px;">PIECES QTY</td>
                    <td style="border: 1px solid #000; font-size: 10px;">PACKAGE PO</td>
                    <td style="border: 1px solid #000; font-size: 10px;">INVOICE</td>
                    <td style="border: 1px solid #000; font-size: 10px;">DIMENSSIONS ITEM / DESCRIPTION / HAZARD</td>
                    <td style="border: 1px solid #000; font-size: 10px;">WEIGHT</td>
                    <td style="border: 1px solid #000; font-size: 10px;">VOLUMEN</td>
                    <td style="border: 1px solid #000; font-size: 10px;">VOL WEIGHT</td>
                    <td style="border: 1px solid #000; font-size: 10px;">LOCATION</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #000; font-size: 10px;">{{ $shipping->pieces  }}</td>
                    <td style="border: 1px solid #000; font-size: 10px;">{{ $shipping->box->name  }}</td>
                    <td style="border: 1px solid #000; font-size: 10px;"></td>
                    <td style="border: 1px solid #000; font-size: 10px;">{{ $shipping->width ? $shipping->width : 0  }}in x {{ $shipping->height ? $shipping->height : 0  }}in x {{ $shipping->length ? $shipping->length : 0  }}in {{ $shipping->description  }}</td>
                    <td style="border: 1px solid #000; font-size: 10px;">{{ $shipping->weight ? $shipping->weight : 0 }}kg</td>
                    <td style="border: 1px solid #000; font-size: 10px;"></td>
                    <td style="border: 1px solid #000; font-size: 10px;"></td>
                    <td style="border: 1px solid #000; font-size: 10px;"></td>
                </tr>
            </tbody>
        </table>
        
        <p class="text-justify" style="font-size: 9px; margin-top: 9rem;">The undersigned Warehouseman claims a lien against the bailor on the goods covered by this warehouse receipt or against the holder or transferee of this receipt, or on the proceeds thereof in its possession for all charges for storage or transportation (including demurrage and terminal charges), insurance, labor or charges present or future in relation to the goods, and for expenses necessary for preservation of the goods, or reasonably incurred in their sale pursuant to law. The undersigned Warehouseman also claims a lien for all like charges and expenses in relation to other goods whenever deposited, whether or not they have been delivered by the Warehouseman. L & C Courier Inc, Inc. shall not be liable for any loss or damage to goods, however caused, unless such loss or damage resulted from failure by L & C Courier Inc, Inc. to exercise proper care and diligence in handling and storing of said goods. The limits of our liability under any Circumstance is $.50 per 100 Lbs., with a maximum of $500.00 per receiving report unless a set declared value is submitted to us in writing prior to receipt of customers goods & charges for such value are paid.</p>

        <table  style="width: 80%;">
            <tbody>
                <tr>
                    <td>
                        <p><strong>{{ \Auth::user()->name }}</stong></p>
                        <hr style="margin-top: -20px; width: 60%; margin-left: -5px;">
                        <p style="margin-top: -10px; font-size: 9px;">Processed by</p>
                    </td>
                    <td>
                        <p style="width: 100%;"><strong>{{ Carbon\Carbon::now()->format('m/d/Y') }}</stong></p>
                        <hr style="margin-top: -20px; width: 60%; margin-left: -5px;">
                        <p style="margin-top: -10px; font-size: 9px;">Date</p>    
                    </td>
                </tr>
            </tbody>
        </table>

         

    </body>
</html>