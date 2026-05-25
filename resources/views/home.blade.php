@extends('layouts.app')

@section(config('app.name'), 'Home')
<!-- Default Statcounter code for mock
https://mock.psalmedu.com -->
<script type="text/javascript">
var sc_project=13240696; 
var sc_invisible=1; 
var sc_security="ff720f52"; 
</script>
<script type="text/javascript"
src="https://www.statcounter.com/counter/counter.js"
async></script>
<noscript><div class="statcounter"><a title="Web Analytics
Made Easy - Statcounter" href="https://statcounter.com/"
target="_blank"><img class="statcounter"
src="https://c.statcounter.com/13240696/0/ff720f52/1/"
alt="Web Analytics Made Easy - Statcounter"
referrerPolicy="no-referrer-when-downgrade"></a></div></noscript>
<!-- End of Statcounter Code -->
@section('content')
<script>
  window.location.href = '{{ route("login") }}';
</script>
<noscript>
  <meta http-equiv="refresh" content="0; url={{ route('login') }}">
  <p>Redirecting to <a href="{{ route('login') }}">login page</a>...</p>
</noscript>
@endsection

