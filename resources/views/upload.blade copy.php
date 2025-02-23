<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">


    <link rel="apple-touch-icon" type="image/png" href="https://cpwebassets.codepen.io/assets/favicon/apple-touch-icon-5ae1a0698dcc2402e9712f7d01ed509a57814f994c660df9f7a952f3060705ee.png" />

    <meta name="apple-mobile-web-app-title" content="CodePen">

    <link rel="icon" type="image/x-icon" href="https://cpwebassets.codepen.io/assets/favicon/favicon-aec34940fbc1a6e787974dcd360f2c6b63348d4b1f4e06c77743096d55480f33.ico" />

    <link rel="mask-icon" type="image/x-icon" href="https://cpwebassets.codepen.io/assets/favicon/logo-pin-b4b4269c16397ad2f0f7a01bcdf513a1994f4c94b8af2f191c09eb0d601762b1.svg" color="#111" />




    <script src="https://cpwebassets.codepen.io/assets/common/stopExecutionOnTimeout-2c7831bb44f98c1391d6a4ffda0e1fd302503391ca806e7fcc7b9b87197aec26.js"></script>


    <title>Bootstrap 4 browse custom button with JQuery</title>

    <link rel="canonical" href="https://codepen.io/mianzaid/pen/GeEbYV">


    <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css'>


    <script>
        window.console = window.console || function(t) {};
    </script>



</head>

<body translate="no">
    <div class="container mt-5">
        <h1 class="text-center">Bootstrap 4 Upload multiple files</h1>
        <div class="col-sm-12 col-lg-4 mr-auto ml-auto border p-4">
            <form method="post" enctype="multipart/form-data" action="{{ route('uploadOnMinio') }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label><strong>Upload Files</strong></label>
                    <div class="custom-file">
                        <input type="file" name="files" multiple class="custom-file-input form-control" id="customFile">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" name="upload" value="upload" id="upload" class="btn btn-block btn-dark"><i class="fa fa-fw fa-upload"></i> Upload</button>
                </div>
            </form>
        </div>
        <script src='https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js'></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js'></script>
        <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js'></script>
        <script id="rendered-js">
            $(document).ready(function() {
                $('input[type="file"]').on("change", function() {
                    let filenames = [];
                    let files = this.files;
                    if (files.length > 1) {
                        filenames.push("Total Files (" + files.length + ")");
                    } else {
                        for (let i in files) {
                            if (files.hasOwnProperty(i)) {
                                filenames.push(files[i].name);
                            }
                        }
                    }
                    $(this).
                    next(".custom-file-label").
                    html(filenames.join(","));
                });
            });
            //# sourceURL=pen.js
        </script>


</body>

</html>