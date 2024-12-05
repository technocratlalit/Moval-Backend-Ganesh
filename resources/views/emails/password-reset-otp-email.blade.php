<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:v="urn:schemas-microsoft-com:vml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" />
    <!--[if !mso]--><!-- -->
    <!--<![endif]-->

    <title>OTP Verification</title>

    <style type="text/css">
        body {
            width: 100%;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            mso-margin-top-alt: 0px;
            mso-margin-bottom-alt: 0px;
            mso-padding-alt: 0px 0px 0px 0px;
            font-family: Arial, sans-serif, 'Open Sans';
        }

        .container590 {
            background-color: #fff;
        }

        p,
        h1,
        h2,
        h3,
        h4 {
            margin-top: 0;
            margin-bottom: 0;
            padding-top: 0;
            padding-bottom: 0;
        }

        span.preheader {
            display: none;
            font-size: 1px;
        }

        html {
            width: 100%;
        }

        table {
            font-size: 14px;
            border: 0;
        }
        /* ----------- responsivity ----------- */

        @media only screen and (max-width: 640px) {
            /*------ top header ------ */
            .main-header {
                font-size: 20px !important;
            }
            .main-section-header {
                font-size: 28px !important;
            }
            .show {
                display: block !important;
            }
            .hide {
                display: none !important;
            }
            .align-center {
                text-align: center !important;
            }
            .no-bg {
                background: none !important;
            }
            /*----- main image -------*/
            .main-image img {
                width: 440px !important;
                height: auto !important;
            }
            /* ====== divider ====== */
            .divider img {
                width: 440px !important;
            }
            /*-------- container --------*/
            .container590 {
                width: 440px !important;
            }
            .container580 {
                width: 400px !important;
            }
            .main-button {
                width: 220px !important;
            }
            /*-------- secions ----------*/
            .section-img img {
                width: 320px !important;
                height: auto !important;
            }
            .team-img img {
                width: 100% !important;
                height: auto !important;
            }
        }

        @media only screen and (max-width: 479px) {
            /*------ top header ------ */
            .main-header {
                font-size: 18px !important;
            }
            .main-section-header {
                font-size: 26px !important;
            }
            /* ====== divider ====== */
            .divider img {
                width: 280px !important;
            }
            /*-------- container --------*/
            .container590 {
                width: 280px !important;
            }
            .container590 {
                width: 280px !important;
            }
            .container580 {
                width: 260px !important;
            }
            /*-------- secions ----------*/
            .section-img img {
                width: 280px !important;
                height: auto !important;
            }
        }
    </style>
    <!--[if gte mso 9]><style type=”text/css”>
        body {
        font-family: arial, sans-serif!important;
        }
        </style>
    <![endif]-->
</head>


<body class="respond" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <!-- pre-header -->

    <!-- pre-header end -->
    <!-- header -->
    <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="f9f9f9">

        <tr>
            <td align="center">
                <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590" style="background-color: #ff4747;">

                    <tr>
                        <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                    </tr>

                    <tr>
                        <td align="center">
                            <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590" style="background-color: #ff4747;">
                                <tr>
                                    <td align="center" height="70" style="height:70px;">
                                    <a href="" style="display: block; border-style: none !important; border: 0 !important;"><img width="300" border="0" style="display: block; width: 300px;" src="{{asset('logo/logo.png')}}" alt="Moval" /></a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
    <!-- end header -->

    <!-- big image section -->

    <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="f9f9f9" class="bg_color">

        <tr>
            <td align="center">
                <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">


                    <tr>
                        <td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
                    </tr>

                    <tr>
                        <td align="left">
                            <table border="0" width="590" align="center" cellpadding="0" cellspacing="0" class="container590" >
                                <tr>
                                    <td align="left" style="color: #757575; font-size: 16px;line-height: 24px;padding: 10px;">
                                        <!-- section text ======-->

                                        <p style="line-height: 24px; margin-bottom:20px;font-size: 24px;color: #4a4a4a">
                                            <b>Hello {{$maildata['fullName']}},</b>
                                        </p>
                                        <p style="line-height: 24px;margin-bottom:20px;color: #4a4a4a">
                                            We'll be happy to help!
There was a request to change your password! If you did not make this request then please ignore this email. Otherwise, use this OTP to change your password.
                                        </p>

                                        <p style="line-height: 24px; margin-bottom:20px;color: #4a4a4a">
                                            OTP: <b>{{$maildata['otp']}}</b>
                                        </p>
                                        <p style="line-height: 24px; margin-bottom:20px;color: #4a4a4a">
                                            <b>Note: Do not share this OTP with anyone.</b>
                                        </p>
                                        <p style="line-height: 24px; margin-bottom:20px;">
                                            For any query, please contact us at <a href="mailto:support@techkrate.com">support@techkrate.com</a>
                                        </p>

                                        <p style="line-height: 24px;color: #4a4a4a;">
                                            Best Wishes
                                        </p>
                                        <p style="line-height: 24px; margin-bottom:30px;color: #4a4a4a;">
                                            Moval Team
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- end section -->


    <!-- main section -->


    <!-- end section -->



    <!-- footer ====== -->
    <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="f9f9f9">
        <tr>
            <td align="center">
                <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590" style="background-color:#f0f0f0">
                    <tr>
                        <td align="center"  style="color: #aaaaaa; font-size: 14px;line-height: 50px;text-align: center;">
                            <span style="color: #333333;">Powered by techkrate.com</span>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>

        <tr>
            <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
        </tr>

    </table>
    <!-- end footer ====== -->

</body>

</html>
