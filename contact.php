<?php

if(isset($_POST['submit'])) 
{
$name=$_POST['first_name'];
$last=$_POST['last_name'];
$email=$_POST['email'];
$phone=$_POST['number'];
$msg=$_POST['msg'];


$body  = '<div style="font-family:Arial,Helvetica,sans-serif; line-height:18px;">
         <p>Dear Admin,</p>
         <p>You have received a message from <b>'.$name.'</b>. Contact him/her immediately.</p>
                <table align="center" width="500px" cellpadding="5">
				<thead style="background-color:#CCC">
				<tr><th colspan="2"><h3>CONTACT MESSAGE</h3></th></tr>
				</thead>
				<tbody style="background-color:#eee">
				<tr><th>First Name</th><td><b>'.$name.'</b></td></tr>
				<tr><th>Last Name</th><td><b>'.$last.'</b></td></tr>
				<tr><th>Email</th><td><b>'.$email.'</b></td></tr>
				<tr><th>Contact Number</th><td><b>'.$phone.'</b></td></tr>
				<tr><th>Message</th><td><b>'.$msg.'</b></td></tr>
				</tbody>
				</table>
			<br>
			<div style="border-top:1px solid #555; padding:10px; text-align:center;">
			<strong>BitVoice Solutions Private Limited</strong>
			<br>
			<div class="yj6qo"></div><div class="adL"></div>
			</div>        
			<br>
			<div class="yj6qo"></div><div class="adL"></div>
		 </div>';
	
        $from  = $email;
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= "From:<$from>" . "\r\nReply-to: $from";
	   /* $to_email="techsofttest@gmail.com";*/
		$to_email="info@bitvoice.in";
	 
		$subject="Contact Us";
		$success=mail($to_email,$subject,$body,$headers);
		
if($success)
 {
	  $msg = "You will be contacted soon.";
	  $_SESSION["sess_Msg"]=$msg;
	  $_SESSION["msg_type"]='success';

  }
  else
  {
	  $msg="Unexpected Error Occured!";
	  $_SESSION['sess_Msg']=$msg;
	  $_SESSION["msg_type"]='error';

  }
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>BitVoice :: Contact</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <link href="css/global.css" rel="stylesheet">
    <link href="css/Responsive.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    
    <link rel="stylesheet" href="font-awesome/css/font-awesome.css">
	<link rel="stylesheet" href="css/bootstrap-responsive-tabs.css">
    
    <link rel="stylesheet" href="js/valid/validationEngine.jquery.css" type="text/css"/>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  
   
    
<!--<navbar start>-->
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
  <div class="row">
      <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
          <div class="social-icons">
            <p>ISO 9001:2008 Certified</p>
            <!--<p>Get social with us!</p>-->
            <!--<ul>
              <li><a href="https://www.facebook.com/bitvoice.in/" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
              <li><a href="https://twitter.com/Bitvoicesoln" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
              <li><a href="javascript:void();" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
              <li><a href="javascript:void();"> target="_blank"<i class="fa fa-skype" aria-hidden="true"></i></a></li>
            </ul>-->
          </div>
      </div>
      
      <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
   
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
          <!--<a class="navbar-brand" href="#">Brand</a>--> 
        </div>
        
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="index.html">home</a></li>
            <li><a href="about.html">About us</a></li>
            <li><a href="benefit.html"> Benefit </a></li>
            <li class="dropdown"> <a href="javascript:void();" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Our Products<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="call-centre-solutions.html">Call Centre Solutions</a></li>
                <li><a href="audio-conference-solutions.html">Audio Conference Solutions</a></li>
                <li><a href="ippbx.html">IPPBX</a></li>
                <li><a href="voice-broadcast-solutions.html">Voice Broadcast Solutions</a></li>
                <li><a href="voice-loggers.html">Voice Loggers</a></li>
                <li><a href="custom-voice-solutions.html">Custom Voice Solutions</a></li>
              </ul>
            </li>
            <li class="active"><a href="contact.php">Contact</a></li>
          </ul>
        </div>
        <!-- /.navbar-collapse -->
      </div>   
    
    </div>
  </div>
  <!-- /.container-fluid --> 
</nav>
<!--<End Navbar>-->   

<!-- start logo-section-bg -->
<div class="logo-section-bg">
	<div class="container">
    	<div class="row">
        	<div class="col-lg-4 col-md-4 col-sm-3 col-xs-12 logo"> 
            	<div class="">
                	<a href="index.html"><img src="images/main-logo.png" class="img-responsive"></a> 
                </div>
            </div>
            <!-- start quickInfo -->
                <div class="col-lg-8 col-md-8 col-sm-8 quickInfo">
                	<ul>
                    	<li>
                        	<div class="icons"><i class="fa fa-at" aria-hidden="true"></i></div>
                            <p>Email Us at <span><a href="mailto:info@bitvoice.in">info@bitvoice.in</a></span></p>
                        </li>
                        <li>
                        	<div class="icons"><i class="fa fa-mobile" aria-hidden="true"></i></div>
                            <p>Any Questions? Call Us: <span>1800-425-66610</span></p>
                        </li>
                        <li>
                        	<div class="icons"><i class="fa fa-globe" aria-hidden="true"></i></div>
                            <p>Explore Us <span><a href="http://www.bitvoicesolutions.com/">www.bitvoice.in</a></span></p>
                        </li>
                    </ul>
                </div>
                <!-- end quickInfo --> 
        </div>
    </div>
</div>
<!-- end logo-section-bg -->
    
<!--<start banner section> --> 
<div class="container-fluid">
  <div class="innerbanner">
    <img src="images/contact-banner.jpg" class="img-responsive">
  </div>
</div>
<!--<End banner section> -->

<!--<start BITVOICE CONFERENCE>-->
<section> 
<div id="msg"> 

<div class="innerWrap">
	<div class="container">
		<h2>Give a Message</h2>
        <p>The best way to contact us is to leave a message and will be in touch with you at the earliest.</p>
        
        <div class="contactWrap">
        	<div class="row">
            	<!-- start contactForm -->
                <form action="" method="post" id="myForm" name="myForm">
                
				   <?php if(isset($_SESSION["sess_Msg"]) && isset($_SESSION["msg_type"]) && $_SESSION["msg_type"]=='success') { ?>
                  <div class="alert alert-success">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong><?php echo $_SESSION["sess_Msg"]; ?></strong> 
                  </div>
                  <?php  unset($_SESSION["sess_Msg"]);} ?>   
                  
                  <?php if(isset($_SESSION["sess_Msg"]) && isset($_SESSION["msg_type"]) && $_SESSION["msg_type"]=='error') { ?>
                  <div class="alert alert-danger">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong><?php echo $_SESSION["sess_Msg"]; ?></strong> 
                  </div>
                  <?php unset($_SESSION["sess_Msg"]); } ?> 
                  
                <div class="col-lg-8 col-md-8 col-sm-8">
                	<div class="contactForm wow fadeInLeft">
                    <div class="row">
                    	<div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>First Name</label>
                                <input name="first_name" type="text" class="form-control validate[required]" />
                            </div>
                        </div>
                    	<div class="col-lg-6 col-md-6 col-sm-6">                    
                            <div class="form-group">
                                <label>Last Name</label>
                                <input name="last_name" type="text" class="form-control validate[required]"/>
                            </div>
                        </div>
                        
                        
                    	<div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>Your Email Address</label>
                                <input name="email" type="email" class="form-control validate[required,custom[email]]"/>
                            </div>
                        </div>
                    	<div class="col-lg-6 col-md-6 col-sm-6">                    
                            <div class="form-group">
                                <label>Contact number</label>
                                <input name="number" type="text" class="form-control" />
                            </div>
                        </div>
                    	               
                        
                    	<div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Your Message</label>
                                <textarea name="msg" cols="" rows="" class="form-control validate[required]" ></textarea>
                            </div>
                        </div>                          
                        
                    	<div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group noBottomMargin">
                                <button type="submit" name="submit" class="sendQueryBtn">Send Message</button>
                            </div>
                        </div>        
                    </div> 
                    </div>                   
                </div>
                </form>
                <!-- end contactForm -->
                
                
                <div class="col-lg-4 col-md-4 col-sm-4">
                	<!-- start contactPageInfo -->
                    <div class="contactPageInfo wow fadeInRight">
                    	<h3>Locate Us:</h3>
                        <hr />
                        <p>
	Heavenly Plaza, Door No.XI/275-J8<br>
	Apartment No: CS-1, First Floor<br>
	Civil Line Road, Chembumukku<br>
	Vazhakkala,  Kakkanad- 682 021
                        </p>
                        
                    	<h3>Call Us:</h3>
                        <hr />
                        <p>Call us at <strong>0484-2881750 </strong><br /> 
                        We will get back to you soon.</p>
                        
                    	<h3>Email Us:</h3>
                        <hr />
                        <p>Email us at <a href="mailto:info@bitvoice.in"><strong>info@bitvoice.in</strong></a> / <a href="mailto:support@bitvoice.in "><strong>support@bitvoice.in </strong></a>
                        </p>
                    </div>
                    <!-- end contactPageInfo -->
                </div>
            </div>
        </div>
        
	</div>
</div>

</div>
<!--<Footer Start here>-->
<footer>
  <div class="container">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 foo-about"> <img src="images/foo-logo.png" class="img-responsive">
      <p>The core Bit Voice Team comprises of three founding members who come with a collective industry-specific experience of over 30 years. Our extensive work experience in the areas of voice-based software design, development and execution which also includes the technical knowhow, pitching and maintenance capabilities worked very well for us when we combined forces under our brand entity, Bit Voice. </p>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 quick-links">
      <h4>Quick Links</h4>
      <div class="col-sm-12">
        <div class="col-sm-6">
          <ul class="foo-links">
            <li><a href="index.html">Home </a></li>
            <li><a href="about.html">About us </a></li>
            <li><a href="javascript:void();">Our Products</a>
              </l>
          </ul>
        </div>
        <div class="col-sm-6">
          <ul class="foo-links">
            <li><a href="benefit.html">Benefit</a></li>
            <li><a href="contact.php">Contact us</a></li>
          </ul>
        </div>
      </div>
      <div class="col-sm-12">
        <ul class="foo-social">
          <li><a href="https://www.facebook.com/bitvoice.in/" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
          <li><a href="https://twitter.com/Bitvoicesoln" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
          <li><a href="javascript:void();" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
          <li><a href="javascript:void();" target="_blank"><i class="fa fa-skype" aria-hidden="true"></i></a></li>
        </ul>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 quick-contact">
      <h4>Quick Contact</h4>
      <ul>
        <li><i class="fa fa-mobile" aria-hidden="true"></i>Phone : +91-484-2881750</li>
        <li><i class="fa fa-envelope-o" aria-hidden="true"></i><a href="mailto:support@bitvoice.in">support@bitvoice.in</a> / <a href="mailto:info@bitvoice.in">info@bitvoice.in</a></li>
        <li class="location"><i class="fa fa-map-marker" aria-hidden="true"></i> 
	Heavenly Plaza, Door No.XI/275-J8<br>
	Apartment No: CS-1, First Floor<br>
	Civil Line Road, Chembumukku<br>
	&nbsp&nbsp&nbsp&nbsp&nbsp Vazhakkala,  Kakkanad- 682 021
		  </li>
      </ul>
    </div>
  </div>
</footer>
<!--<Footer End here>-->

<section class="copyright-bg">
  <div class="container">
    <div class="copyright">
      <p>Copyright &copy; 2017 BitVoice Solutions Private Limited, All Right Reserved<br>
        Powered by <a href="http://www.techsoftweb.com/" target="_blank">Techsoft</a></p>
    </div>
    <a href="javascript:void();" class="go-top"><i class="fa fa-angle-up" aria-hidden="true"></i></a>
  </div>
</section>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.min.js"></script> 

<!-- For Tab -->
<script src="js/jquery.bootstrap-responsive-tabs.min.js"></script>

<!-- For Banner Slider -->
<script type="text/javascript" src="js/jquery.flexisel.js"></script> 

<script type="text/javascript" src="js/custom.js"></script> 

<script src="js/valid/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="js/valid/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
		jQuery(document).ready(function(){
			
			// binds form submission and fields to the validation engine
			jQuery("#myForm").validationEngine();
			
		});
</script> 



  </body>
</html>