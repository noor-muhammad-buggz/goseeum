@if (session('message') || session('error'))

    @if (session('message'))
        <div class="alert alert-success">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
            <p>@php echo session('message') @endphp</p>
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
            <p>@php echo session('error') @endphp</p>
        </div>
    @else
        <!-- <div class="alert alert-danger">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <ul>
                @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div> -->
    @endif

@endif
                        