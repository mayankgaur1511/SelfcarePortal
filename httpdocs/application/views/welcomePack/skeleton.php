<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Welcome to Arkadin</title>
    <style type="text/css">
        /* CLIENT-SPECIFIC STYLES */
        
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        /* Prevent WebKit and Windows mobile changing default text sizes */
        
        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        /* Remove spacing between tables in Outlook 2007 and up */
        
        img {
            -ms-interpolation-mode: bicubic;
        }
        /* Allow smoother rendering of resized image in Internet Explorer */
        /* RESET STYLES */
        
        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }
        
        table {
            border-collapse: collapse !important;
        }
        
        body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        /* iOS BLUE LINKS */
        
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }
        
        .ReadMsgBody {
            width: 100%;
            background-color: #e6e6e6;
        }
        
        .ExternalClass {
            width: 100%;
            background-color: #e6e6e6;
        }
        
        body {
            width: 100%;
            background-color: #e6e6e6;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            font-family: Calibri, Times, serif
        }
        
        table {
            border-collapse: collapse;
        }
        
        @media only screen and (max-width: 640px) {
            body[yahoo] .deviceWidth {
                width: 440px!important;
                padding: 0;
            }
            body[yahoo] .deviceWidth320 {
                width: 480px!important;
                padding: 0;
            }
            .deviceWidthImage {
                width: 480px!important;
            }
            body[yahoo] .center {
                text-align: center!important;
            }
            .devicenone {
                display: none!important;
            }
            .devicecenter {
                text-align: center!important;
                margin: 0 140px 0 auto!important;
                width: 150px;
            }
            .deviceleft {
                text-align: left !important;
            }
            .companylogo {
                text-align: center!important;
                margin: 0!important;
                width: 150px;
            }
            .productlogo {
                padding: 10px 0 0 0!important;
                text-align: center!important;
            }
            .tagline-height {
                height: 40px!important;
            }
            ***** LITMUS CODE ******
            /* ALLOWS FOR FLUID TABLES */
            .wrapper {
                width: 100% !important;
                max-width: 100% !important;
            }
            /* ADJUSTS LAYOUT OF LOGO IMAGE */
            .logo img {
                margin: 0 auto !important;
            }
            /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
            .mobile-hide {
                display: none !important;
            }
            .img-max {
                max-width: 100% !important;
                width: 100% !important;
                height: auto !important;
            }
            /* FULL-WIDTH TABLES */
            .responsive-table {
                width: 100% !important;
            }
            /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
            .padding {
                padding: 10px 5% 15px 5% !important;
            }
            .padding-meta {
                padding: 30px 5% 0px 5% !important;
                text-align: center;
            }
            .padding-copy {
                g: 10px 5% 10px 5% !important;
                text-align: center;
            }
            .no-padding {
                padding: 0 !important;
            }
            .section-padding {
                padding: 50px 15px 50px 15px !important;
            }
            /* ADJUST BUTTONS ON MOBILE */
            .mobile-button-container {
                margin: 0 auto;
                width: 100% !important;
            }
            .mobile-button {
                padding: 5px !important;
                border: 0 !important;
                font-size: 16px !important;
                display: block !important;
            }
            .mobile-button-container-pt {
                width: 100% !important;
            }
            .mobile-button-pt {
                padding: 5px !important;
                border: 0 !important;
                font-size: 16px !important;
                display: block !important;
            }
        }
        
        @media only screen and (max-width: 479px) {
            .title {
                font-size: 18px !important;
            }
            .deviceWidthImage {
                width: 0px!important;
            }
            body[yahoo] .deviceWidth {
                width: 280px!important;
                padding: 0;
            }
            body[yahoo] .deviceWidth320 {
                width: 320px!important;
                padding: 0;
            }
            body[yahoo] .center {
                text-align: center!important;
            }
            .devicenone {
                display: none!important;
            }
            .companylogo {
                text-align: center!important;
                margin: 0!important;
                width: 150px;
            }
            .productlogo {
                padding: 10px 0 0 0!important;
                text-align: center!important;
            }
            .deviceleft {
                text-align: left !important;
            }
            .img-max {
                max-width: 100% !important;
                width: 100% !important;
                height: auto !important;
            }
        }
    </style>
</head>

<body marginwidth="0" marginheight="0" bgcolor="#e6e6e6" leftmargin="0" topmargin="0" yahoo="fix" style="margin: 0 !important; padding: 0 !important;" class="">
    <!-- WRAPPER -->
    <table width="70%" cellspacing="0" cellpadding="0" border="0" align="center" class="responsive-table">
        <tbody>
            <tr>
                <td width="100%" valign="top" class="" style="padding:20px 0 20px 0;">

                <!-- ################## HEADER ################# -->
				
                <!-- ---------- Dynamic Part : Header ---------- -->
                <span elqid="4952" elqtype="DynamicContent" class="remove-absolute" style="display: block">
                    <?php $this->load->view("welcomePack/header") ?>
                </span>
                <!-- ------- End of Dynamic Part : Header ------ --> 

                    
                <!-- ################# PRODUCTS ################ -->
                  
                <!--  Dynamic Part : Anytime --------- -->
                <span elqid="4957" elqtype="DynamicContent" class="remove-absolute" style="display: block">
                    <?php if(isset($anytime)): ?>
                        <?php $this->load->view("welcomePack/anytime"); ?>
                    <?php endif ?>
                </span>
                <!-- ------- End of Dynamic Part : Anytime ----- --> 

                <!-- --------- Dynamic Part : Anywhere --------- -->
                <span elqid="4956" elqtype="DynamicContent" class="remove-absolute" style="display: block">
                    <?php if(isset($anywhere)): ?>
                        <?php $this->load->view("welcomePack/anywhere"); ?>
                    <?php endif ?>
                </span>
                <!-- ------ End of Dynamic Part : Anywhere ----- -->
                  

                <!-- ----------- Dynamic Part : Webex ---------- -->
                <span elqid="4961" elqtype="DynamicContent" class="remove-absolute" style="display: block">
                    <?php if(isset($webex)): ?>
                        <?php $this->load->view("welcomePack/webex"); ?>
                    <?php endif ?>
                </span>
                <!-- -------- End of Dynamic Part : Webex ------ -->
				  
                <!-- ############ ONE PORTAL & HELP  ########### -->

                <!-- -------- Dynamic Part : One Portal -------- -->
                <span elqid="4591" elqtype="DynamicContent" class="remove-absolute" style="display: block"></span>
                <!-- ------- End of Dynamic Part : Admin ------- --> 
				
                <!-- ----------- Dynamic Part : Help ----------- -->
                <span elqid="4954" elqtype="DynamicContent" class="remove-absolute" style="display: block"></span>
                <!-- -------- End of Dynamic Part : Help ------- --> 
                  
				  
                <!-- ########### CS SUPPORT & FOOTER ########### -->
                 
                <!-- ----------- Dynamic Part : Footer --------- -->
                <span elqid="5194" elqtype="DynamicContent" class="remove-absolute" style="display: block">
                    <?php $this->load->view("welcomePack/footer"); ?>
                </span>
                <!-- ------- End of Dynamic Part : Footer ------ --> 
                </td>
            </tr>
        </tbody>
    </table>
    <!-- WRAPPER -->
</body>
</html>