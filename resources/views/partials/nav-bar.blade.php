<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
          <a class="navbar-brand" href="#">WeiSy</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="{{Request::is('weighing/*') ? 'active' : ''}}"><a href="{{route('weighing.create')}}">Registrar Peso</a></li>
                <li class="{{Request::is('employee/importData') ? 'active' : ''}}"><a href="{{route('employee.importDataForm')}}">Importar Datos de Empleados</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>