<x-mail::message>
    # Delayed Amortization Notification

    An amortization payment has been processed after its scheduled date.

    **Amortization ID:** {{ $amortization->id }}
    **Project** {{ $amortization->project->name }}
    **Amount:** ${{ number_format($amortization->amount / 100, 2) }}
    **Scheduled Date:** {{ $amortization->scheduled_date?->format('Y-m-d') ?? 'N/A' }}
    **Processed Date:** {{ now()->format('Y-m-d') }}

    Thanks <br />
    {{ config('app.name') }}
</x-mail::message>
