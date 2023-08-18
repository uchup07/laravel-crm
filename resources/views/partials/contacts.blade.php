@foreach($contacts as $contact)
	
	@if($contact->entityable)
	<span class="fa fa-building mr-1" aria-hidden="true"></span> <a href="{{ route('laravel-crm.organisations.show',$contact->entityable) }}">{{ $contact->entityable->name }}</a>
	@endif
@endforeach