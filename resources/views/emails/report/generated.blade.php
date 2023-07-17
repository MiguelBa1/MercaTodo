@component('mail::message')
# Report Generated

Hi {{ $report->user->name }},

Your sales report for the period from {{ $report->startDate }} to {{ $report->endDate }} has been generated.

You can view the report by clicking on the button below.

@component('mail::button', ['url' => url(route('admin.report.show', $report->id))])
    View Report
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
