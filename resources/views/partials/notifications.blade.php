
{{-- Error Messages --}}
<div id="system-notifications" class="row">
    <div class="col-sm-8 col-sm-offset-2">
        @if ($errors->has())
            <div class="alert alert-danger alert-dismissible margin-top-10" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>Error!!</strong>
                Hay problemas con la información suministrada.
            </div>
        @endif
        
        {{-- Success Messages --}}
        
        @if (\Session::has('success') && !empty(\Session::get('success')))
            <div class="alert alert-success alert-dismissible margin-top-10" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>Bien hecho!!</strong><br>
                
                @if(is_array(session('success')))
                
                    @foreach(\Session::get('success') as $success)
                        <div>
                        {{ $success }}
                        </div>
                    @endforeach
                    
                @else
                    <div>
                        {{ session('success') }}
                    </div>
                @endif  
                
            </div>
        @endif
        
        {{-- Error Messages --}}
        
        @if (\Session::has('error') && !empty(\Session::get('error')))
            <div class="alert alert-danger alert-dismissible margin-top-10" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>Error!!</strong><br>
                
                @if(is_array(session('error')))
                
                    @foreach(\Session::get('error') as $error)
                        <div>
                        {{ $error }}
                        </div>
                    @endforeach
                    
                @else
                    <div>
                        {{ session('error') }}
                    </div>
                @endif
                
            </div>
        @endif
        
        {{-- Waring Messages --}}
        
        @if (\Session::has('warning') && !empty(\Session::get('warning')))
            <div class="alert alert-warning alert-dismissible margin-top-10" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>Alerta!!</strong><br>
                @if(is_array(session('warning')))
                
                    @foreach(\Session::get('warning') as $warning)
                        <div>
                        {{ $warning }}
                        </div>
                    @endforeach
                
                @else
                    <div>
                        {{ session('warning') }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>