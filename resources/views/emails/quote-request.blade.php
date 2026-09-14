<x-mail::message>
# New Quote Request

**Name:** {{ $quoteRequest->name }}  
**Phone:** {{ $quoteRequest->phone }}  
**Email:** {{ $quoteRequest->email }}  
**Service:** {{ $quoteRequest->service }}

**Message:**  
{{ $quoteRequest->message ?: '—' }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
