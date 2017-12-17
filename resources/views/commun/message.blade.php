
				@if(Session::has('message'))
				<p class="alert alert-info">{{ Session::get('message') }}</p>
				@endif