<!DOCTYPE html>
  <html lang="{{ app()->getLocale() }}">
  <html>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
    window.Laravel = {!! json_encode([
        'csrfToken' => csrf_token(),
    ]) !!};
    </script>


    <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Bootstrap core CSS -->
    <link href="https://bootswatch.com/4/yeti/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="https://getbootstrap.com/docs/4.0/examples/dashboard/dashboard.css" rel="stylesheet">
    </head>

<body>
    <header>
      <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="#">Truck Monitoring</a>
        <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

   

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Settings</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Profile</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Help</a>
            </li>
          </ul>
     
        </div>
      </nav>
    </header>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-sm-4 col-md-4 d-none d-sm-block sidebar">

            <div class="row p-3">
              <div class="form-group col-12">
                  <input type="text" class="form-control" placeholder="Search">

                  <small id="emailHelp" class="form-text text-muted">Search driver from the list below.</small>
              </div>
            </div>


          <ul class="nav nav-pills flex-column scroll-bar">

            <li class="nav-item p-3 border-right-0 border-left-0 border border-secondary">
              <div class="row">
                <div class="col-2 pr-0">
                    <img class="img-fluid rounded-circle" src="{{ asset('/img/avatar.png') }}">
                </div>
                <div class="col-8">
                    <span>TERRENCE CHRISTIAN TEJADA</span><br/>
                    <span>TITAN ASIA HAULER</span><br/>
                    <span>AAA-000</span>

                </div>
                <div class="col-2 text-center">
                </div>
            </div>
            </li>

                  <li class="nav-item p-3 border-right-0 border-left-0 border-top-0 border border-secondary">
              <div class="row">
                <div class="col-2 pr-0">
                    <img class="img-fluid rounded-circle" src="{{ asset('/img/avatar.png') }}">
                </div>
                <div class="col-8">
                    <span>TERRENCE CHRISTIAN TEJADA</span><br/>
                    <span>TITAN ASIA HAULER</span><br/>
                    <span>AAA-000</span>
                </div>
                <div class="col-2 text-center">
                </div>
            </div>
            </li>

            <li class="nav-item p-3 border border-secondary border-right-0 border-left-0 border-top-0">
              <div class="row">
                <div class="col-2 pr-0">
                    <img class="img-fluid rounded-circle" src="{{ asset('/img/avatar.png') }}">
                </div>
                <div class="col-8">
                    <span>TERRENCE CHRISTIAN TEJADA</span><br/>
                    <span>TITAN ASIA HAULER</span><br/>
                    <span>AAA-000</span>
                </div>
                <div class="col-2 text-center">
                </div>
            </div>
            </li>


            <li class="nav-item p-3 border-right-0 border-left-0 border-top-0 border border-secondary">
              <div class="row">
                <div class="col-2 pr-0">
                    <img class="img-fluid rounded-circle" src="{{ asset('/img/avatar.png') }}">
                </div>
                <div class="col-8">
                    <span>TERRENCE CHRISTIAN TEJADA</span><br/>
                    <span>TITAN ASIA HAULER</span><br/>
                    <span>AAA-000</span>
                </div>
                <div class="col-2 text-center">
                </div>
            </div>
            </li>



          </ul>


        </nav>

        <main role="main" class="col-sm-8 ml-sm-auto col-md-8 pt-3">
          <h1>Dashboard</h1>


          <div class="row border border-left-0 bg-primary fixed-bottom message-box-wrapper ml-auto p-5" style="width: 68%">



        </div>  


        </main>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://getbootstrap.com/assets/js/vendor/popper.min.js"></script>
    <script src="https://getbootstrap.com/dist/js/bootstrap.min.js"></script>
  </body>
  </html>