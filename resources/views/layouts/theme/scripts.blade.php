<script src="{{ asset('assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/popper.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>
{{-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
@vite('resources/js/app2.js')


<script>
    $(document).ready(function() {
        App.init();
    });
</script>
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script src="{{ asset('plugins/sweetalerts/sweetalert2.min.js')}}"></script>
<script src="{{ asset('plugins/notification/snackbar/snackbar.min.js')}}"></script>
<script src="{{ asset('plugins/nicescroll/nicescroll.js')}}"></script>
<script src="{{ asset('plugins/currency/currency.js')}}"></script>
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

<script>
    function noty(msg, option = 1)    
    {
        Snackbar.show({
            text: msg.toUpperCase(),
            actionText: 'Cerrar',
            actionTextColor: '#fff',
            backgroundColor: option == 1 ? '#3b3f5c' : '#e7515a',
            pos: 'top-right'
        });
    }
    document.addEventListener("DOMContentLoaded", function() {
        window.livewire.on('global-msg', msg => {
            noty(msg)
        });
    })
  
</script>

<script src="{{ asset('plugins/flatpickr/flatpickr.js')}}"></script>

@livewireScripts
