<html>
<head>
<link rel="stylesheet" href="<?php echo asset('plugin/PPTXjs/css/pptxjs.css'); ?>">
<link rel="stylesheet" href="<?php echo asset('plugin/PPTXjs/css/nv.d3.min.css'); ?>"> <!-- for charts graphs -->

<script type="text/javascript" src="<?php echo asset('plugin/PPTXjs/js/jquery-1.11.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo asset('plugin/PPTXjs/js/jszip.min.js'); ?>"></script> <!-- v2.. , NOT v.3.. -->
<script type="text/javascript" src="<?php echo asset('plugin/PPTXjs/js/filereader.js'); ?>"></script> <!--https://github.com/meshesha/filereader.js -->
<script type="text/javascript" src="<?php echo asset('plugin/PPTXjs/js/d3.min.js'); ?>"></script> <!-- for charts graphs -->
<script type="text/javascript" src="<?php echo asset('plugin/PPTXjs/js/nv.d3.min.js'); ?>"></script> <!-- for charts graphs -->
<script type="text/javascript" src="<?php echo asset('plugin/PPTXjs/js/pptxjs.js'); ?>"></script>
<script type="text/javascript" src="<?php echo asset('plugin/PPTXjs/js/divs2slides.js'); ?>"></script> <!-- for slide show -->
</head>
<body>
<div id="your_div_id_result"></div>

<script type="text/javascript">
    $("#your_div_id_result").pptxToHtml({
        pptxFileUrl: "<?php echo asset('files/material/ppt/1/1/Elements of Decision Making.pptx'); ?>",
        slideMode: false,
        keyBoardShortCut: false,
        mediaProcess: true, /** true,false: if true then process video and audio files */
        slideModeConfig: {  //on slide mode (slideMode: true)
            first: 1,
            nav: false, /** true,false : show or not nav buttons*/
            navTxtColor: "white", /** color */
            navNextTxt:"&#8250;", //">"
            navPrevTxt: "&#8249;", //"<"
            showPlayPauseBtn: false,/** true,false */
            keyBoardShortCut: false, /** true,false */
            showSlideNum: false, /** true,false */
            showTotalSlideNum: false, /** true,false */
            autoSlide: false, /** false or seconds (the pause time between slides) , F8 to active(keyBoardShortCut: true) */
            randomAutoSlide: false, /** true,false ,autoSlide:true */
            loop: false,  /** true,false */
            background: "black", /** false or color*/
            transition: "default", /** transition type: "slid","fade","default","random" , to show transition efects :transitionTime > 0.5 */
            transitionTime: 1 /** transition time in seconds */
        }
    });
</script>
</body>
</html>