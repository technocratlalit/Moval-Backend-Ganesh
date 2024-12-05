<?php
    $imagePreUrl   = "http://demo.moval.techkrate.com/letterhead/";
    $approveText   = "This report is issued without prejudice & generated by VIISLA at the sole request of the party & it is to be used by the sole party for the stated purpose only.<br><br>No Standard price list for vehicle valuation, the valuation indicates in the report is our opinion as per the market value of the said vehicle at the time of inspection. The valuation is based as per vehicle condition at the time of inspection/Valuation.";
   
    $refNo            = "VIISLA/22/";
    $date           = date("d/m/Y");
    $regNo          = "asdasd";
   
    $logoImage      = $imagePreUrl."Letterhead.png";
    $seal           = $imagePreUrl."Signature.png";
    $footer_seal    = $imagePreUrl."Footer_Image.png";
    $footer_image    = $imagePreUrl."Letterfooter.png";
   
    // $invoice_date           = "2022-11-30";
    // $number_of_reports      = "2";
    // $last_date_of_payment   = "2022-11-30";
    // $report_cost            = "13";
    // $bill_amount            = "26";
    // $name                   = "Govind";
    // $address                = "Address -- ";
    // $invoice_id             = "112";
    
    $invoice_id             = $invoice->id;
    $invoice_date           = $invoice->last_date_of_payment;
    $number_of_reports      = $invoice->number_of_reports;
    $last_date_of_payment   = $invoice->last_date_of_payment;
    $report_cost            = $invoice->report_cost;
    $bill_amount            = $invoice->bill_amount;
    $name            = $invoice->name;
    $address            = $invoice->address;

    $bill_amount            = number_format_custom((float)$bill_amount, 2, '.', '');
    $report_cost            = number_format_custom((float)$report_cost, 2, '.', '');
   ?>
<html>
   <head>
      <meta http-equiv=Content-Type content="text/html; charset=utf-8">
      <meta name=Generator content="Microsoft Word 15 (filtered)">
      <title>Invoice #<?php echo $invoice_id;?></title>
      <style>
         <!--
            /* Font Definitions */
            @font-face
               {font-family:Mangal;
               panose-1:2 4 5 3 5 2 3 3 2 2;}
            @font-face
               {font-family:"Cambria Math";
               panose-1:2 4 5 3 5 4 6 3 2 4;}
            @font-face
               {font-family:Verdana;
               panose-1:2 11 6 4 3 5 4 4 2 4;}
            @font-face
               {font-family:Tahoma;
               panose-1:2 11 6 4 3 5 4 4 2 4;}
            @font-face
               {font-family:Times;
               panose-1:2 2 6 3 5 4 5 2 3 4;}
            /* Style Definitions */
            p.MsoNormal, li.MsoNormal, div.MsoNormal
               {margin:0in;
               font-size:12.0pt;
               font-family:"Times New Roman",serif;}
            p.MsoListParagraph, li.MsoListParagraph, div.MsoListParagraph
               {margin-top:0in;
               margin-right:0in;
               margin-bottom:0in;
               margin-left:.5in;
               font-size:12.0pt;
               font-family:"Times New Roman",serif;}
            /* Page Definitions */
            @page WordSection1
               {size:8.5in 11.0in;
               margin:.1in 20.0pt 9.0pt 76.5pt;}
            div.WordSection1
               {page:WordSection1;}
            /* List Definitions */
            ol
               {margin-bottom:0in;}
            ul
               {margin-bottom:0in;}

            #footer {
              position: fixed;
              left: 0;
               right: 0;
               color: #aaa;
               font-size: 0.9em;
            }
            #header {
              top: 0;
               border-bottom: 0.1pt solid #aaa;
            }
            #footer {
              bottom: -45;
              /*border-top: 0.1pt solid #aaa;*/
            }

            @page {
              header: page-header;
              footer: page-footer;
            }

      </style>
   </head>

   <body lang=EN-US link=blue vlink=purple style="word-wrap:break-word;font-family: 'Times New Roman',serif">
      <div style="max-width: 620px;margin: auto;" class=WordSection1>
         <table  style="margin-bottom:2px;width:100%">
            <tr>
               <td style="width:100%;text-align:center;">
                  <img style="vertical-align: middle;width: 100%;" src="<?php echo $logoImage; ?>">
               </td>
            </tr>
         </table>
         
         <table class="MsoNormalTable" border="1"
            style="border-collapse:collapse;border:none;margin-top:10px">
            <tr style="height:43.6pt">
               <td width=613 valign=top style="width:459.9pt;border:1px solid;
                  padding:0in 5.4pt 0in 5.4pt;height:43.6pt">
                  <p class=MsoNormal style="text-align:justify"><b><span 
                     style="font-size:20.0pt">Invoice #<?php echo $invoice_id;?></span></b></p>
                  <p class=MsoNormal><span  style="font-size:14.0pt">&nbsp;</span></p>
                  <p class=MsoNormal><span  style="font-size:14.0pt">Invoice Date: <?php echo date("M d, Y", strtotime($invoice_date));?></span></p>
               </td>
            </tr>
         </table>
         <p class=MsoNormal><span  style="font-size:8.0pt">&nbsp;</span></p>
         <p class=MsoNormal><b><span  style="font-size:14.0pt">&nbsp;</span></b></p>
         <p class=MsoNormal><b><span  style="font-size:14.0pt;font-family:
            Verdana,sans-serif;color:#222222;background:white"><?php echo $name ;?> </span></b></p>
         <p class=MsoNormal><span  style="font-size:14.0pt;font-family:Verdana,sans-serif;
            color:#222222"><?php echo $address ;?></span></p>
         <p class=MsoNormal><span  style="font-size:14.0pt;color:#222222;
            background:white">&nbsp;</span></p>
         <p class=MsoNormal><span  style="font-size:14.0pt">&nbsp;</span></p>
         <table class=MsoNormalTable border=1 cellspacing=0 cellpadding=0 width=622
            style="border-collapse:collapse;border:none">
            <tr style="height:14.3pt">

               <td width=499 valign=top style="width:374.15pt;border:1px solid;
                  padding:0in 5.4pt 0in 5.4pt;height:14.3pt;text-align: center;">
                  <p class=MsoNormal align=center ><b><span
                     style="font-size:14.0pt;text-align:center">Description</span></b></p>
               </td>

               <td width=123 valign=top style="width:92.3pt;border:1px solid;
                  border-left:none;padding:0in 5.4pt 0in 5.4pt;height:14.3pt;text-align: center;">
                  <p class=MsoNormal align=center style="text-align:center"><b><span
                     style="font-size:14.0pt;text-align:center">Total</span></b></p>
               </td>
            </tr>
            <tr style="">
               <td width=499 valign=top style="width:374.15pt;border:1px solid;
                  border-top:none;padding:0in 5.4pt 0in 5.4pt;">
                  <p class=MsoNormal><b><span  style="font-size:14.0pt">Total
                     reports: </b><?php echo $number_of_reports; ?> @ Rs.<?php echo $report_cost; ?> per report</span>
                  </p>
                  <p class=MsoNormal><span  style="font-size:11.0pt">For the month of
                     <?php echo date("M Y", strtotime($invoice_date));?></span>
                  </p>
               </td>
               <td style="text-align: right;width:92.3pt;border-top:none;border-left:
                  none;border-bottom:1px solid;border-right:1px solid;
                  padding:0in 5.4pt 0in 5.4pt;">
                  <p class=MsoNormal align=right style="text-align:right"><b><span 
                     style="font-size:14.0pt"><?php echo $bill_amount;?></span></b></p>
                  <br></br><br></br><br></br><br></br><br></br><br></br>

               </td>
            </tr>
            <tr style="height:14.0pt">
               <td width=499 valign=top style="text-align: right;width:374.15pt;border:1px solid;
                  border-top:none;padding:0in 5.4pt 0in 5.4pt;height:14.0pt">
                  <p class=MsoNormal align=right style="text-align:right"><b><span 
                     style="font-size:14.0pt">Gross Amount</span></b></p>
               </td>
               <td style="text-align: right;border-top:none;border-left:
                  none;border-bottom:1px solid;border-right:1px solid;
                  padding:0in 5.4pt 0in 5.4pt;height:14.0pt">
                  <p class=MsoNormal align=right style="text-align:right"><b><span 
                     style="font-size:14.0pt"><?php echo $bill_amount;?></span></b></p>
               </td>
            </tr>
         </table>
         <p class=MsoNormal><span  style="font-size:14.0pt">&nbsp;</span></p>
         <p class=MsoNormal><span  style="font-size:14.0pt">PAN</span><span
            style="font-size:14.0pt">: </span><span  style="font-size:
            14.0pt">ADNPC1330K</span></p>
         <p class=MsoNormal><b><span  style="font-size:10.0pt">&nbsp;</span></b></p>
         <p class=MsoNormal><b><span  style="font-size:10.0pt">TERMS &amp; CONDITIONS:</span></b></p>
         <ol style="font-size:10.0pt;">
            <li style="margin-top:4px;">Subject to jurisdiction of Delhi NCR Courts only;</li>
            <li style="margin-top:4px;">Payment should be made through Moval platform only.</li>
            <li style="margin-top:4px;">Any out of the platform payment will take 24 hours in reflection and will be made to our UPI no. 9871201022.</li>
            <li style="margin-top:4px;">Dispute relating to this bill/Debit Note must be submitted within one week from the receipt of this bill/Debit Note on our customer support email id <b>support@techkrate.com</b>.</li>
         </ol>

         
         
      </div>


      <htmlpagefooter name="page-footer">
          <!-- <div style="margin-top:30px" class="page-number"></div> -->
            
            <table  style="margin-bottom:2px;width:100%">
            <tr>
               <td style="width:100%;text-align:center;">
                  <img style="vertical-align: middle;width: 100%;" src="<?php echo $footer_image; ?>">
               </td>
            </tr>
         </table>
        </htmlpagefooter>

        
   </body>
</html>