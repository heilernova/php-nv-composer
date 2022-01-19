<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API REST - nv | code</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> -->
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Libre+Baskerville&family=Nunito:wght@200&family=Roboto+Condensed:wght@300&display=swap');

    body{
        font-family: 'Libre Baskerville', serif;
        font-family: 'Nunito', sans-serif;
        font-family: 'Roboto Condensed', sans-serif;

        position: absolute;
        left: 0; right: 0; top: 0; bottom: 0;
        display: grid;
        grid-template-rows: auto 1fr auto;
        margin: 0;
        /* background-color: gainsboro; */
    }
    header h1{
        font-stretch: extra-expanded;
    }
    header{
        background-color:  #0288D1;
        display: flex;
        justify-content: center;
    }
    .content{
        margin: 30px 0;
        display: flex;
        justify-content: center;
    }
    footer{
        background-color: #026296;
        color: aliceblue;
        padding: 20px;
        /* height: 110px; */
        font-size: 130%;
        font-weight: 600;
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
    }
    footer .web{
        color: aliceblue;
        display: flex;
        justify-content: center;
    }
    footer .web a{
        color: aliceblue;
        text-decoration: none;
    }

    footer .contact{
        display: flex;
        justify-content: right;
    }
</style>
<body>
    <header>
        <h1>
            NOVA | CODE - API REST
        </h1>
    </header>
    <div class="content">
        <div>
            <h2>
                Proyect name: 
                <?php
                    $setting = \PhpNv\nv\nv::getSettigns();
                    echo $setting->application->getName()
                ?>
            </h2>
            <br>
            <h2>
                Type application: api-rest json
            </h2>
        </div>
    </div>
    <footer>
        <div class="author">
            Desarrollador: Heiler Nova.
        </div>
        <div class="web">
            <a href="http://www.nv-code.com" target="_blank" rel="noopener noreferrer">www.nv-code.com</a>
        </div>
        <div class="contact">
            Contacto
        </div>
    </footer>
</body>
</html>