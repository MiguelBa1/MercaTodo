<x-mail::message>
# Hello, {{ $user->name }}

There was an error generating your sales report for the period from {{ $report->startDate }} to {{ $report->endDate }}.

Please, try again later.

If the problem persists, please contact us at {{ config('mail.from.address') }}.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
