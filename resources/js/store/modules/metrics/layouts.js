/**
 * Primary Table Layouts with Headings
 */

export default [
    {
        name: 'acquisition',
        label: 'Acquisition Metrics',
        headings: ['leads', 'spend', 'cpl'],
        cols: ['name', 'leads', 'leadsBudgetPaceDelta', 'spend', 'spendBudgetDelta', 'cpl', 'cplBudgetDelta']
    },
    {
        name: 'revenue',
        label: 'Revenue Metrics',
        headings: ['starts', 'students', 'revenue'],
        cols: ['name', 'starts', 'startsBudgetDelta', 'students', 'studentsBudgetDelta', 'revenue', 'revenueBudgetDelta']
    },
    {
        name: 'leadBudget',
        label: 'Lead Budget',
        headings: ['leads', 'leadsBudgetPace', 'leadsBudgetPaceDelta'],
        cols: ['name', 'leads', 'leadsBudgetPace', 'leadsBudgetPaceDelta', 'leadsBudget', 'leadsBudgetPercent']
    },
    {
        name: 'leadForecast',
        label: 'Lead Forecast',
        headings: ['leads', 'leadsForecastPace', 'leadsForecastPaceDelta'],
        cols: ['name', 'leads', 'leadsForecastPace', 'leadsForecastPaceDelta', 'leadsForecast', 'leadsForecastPercent']
    },
    {
        name: 'spendBudget',
        label: 'Spend Budget',
        headings: ['spend', 'spendBudget', 'spendBudgetDelta'],
        cols: ['name', 'spend', 'spendBudget', 'spendBudgetDelta', 'spendBudgetPercent']
    },
    {
        name: 'spendForecast',
        label: 'Spend Forecast',
        headings: ['spend', 'spendForecast', 'spendForecastDelta'],
        cols: ['name', 'spend', 'spendForecast', 'spendForecastDelta', 'spendForecastPercent']
    },
    {
        name: 'cpl',
        label: 'CPL',
        headings: ['cpl', 'cplBudgetDelta', 'cplForecastDelta'],
        cols: ['name', 'cpl', 'cplBudget', 'cplBudgetDelta', 'cplForecast', 'cplForecastDelta']
    },
    {
        name: 'contact',
        label: 'Contact',
        headings: ['contact', 'contactRate', 'cpcontact'],
        cols: ['name', 'contact', 'contactRate', 'cpcontact', 'spend', 'leads']
    },
    {
        name: 'contact15',
        label: 'Contact 15',
        headings: ['contact15', 'contact15Rate', 'cpcontact15'],
        cols: ['name', 'contact15', 'contact15Rate', 'cpcontact15', 'spend', 'leads']
    },
    {
        name: 'quality',
        label: 'Quality',
        headings: ['quality', 'qualityRate', 'cpquality'],
        cols: ['name', 'quality', 'qualityRate', 'cpquality', 'spend', 'leads']
    },
    {
        name: 'quality30',
        label: 'Quality 30',
        headings: ['quality30', 'quality30Rate', 'cpquality30'],
        cols: ['name', 'quality30', 'quality30Rate', 'cpquality30', 'spend', 'leads']
    },
    {
        name: 'aip',
        label: 'Applications',
        headings: ['aip', 'aipRate', 'cpaip'],
        cols: ['name', 'aip', 'aipRate', 'cpaip', 'spend', 'leads']
    },
    {
        name: 'app30',
        label: 'App 30',
        headings: ['app30', 'app30Rate', 'cpapp30'],
        cols: ['name', 'app30', 'app30Rate', 'cpapp30', 'spend', 'leads']
    },
    {
        name: 'comfile',
        label: 'Complete File',
        headings: ['comfile', 'comfileRate', 'cpcomfile'],
        cols: ['name', 'comfile', 'comfileRate', 'cpcomfile', 'spend', 'leads']
    },
    {
        name: 'comfile60',
        label: 'Com. File 60',
        headings: ['comfile60', 'comfile60Rate', 'cpcomfile60'],
        cols: ['name', 'comfile60', 'comfile60Rate', 'cpcomfile60', 'spend', 'leads']
    },
    {
        name: 'accepted',
        label: 'Accepted',
        headings: ['acc', 'accRate', 'cpacc'],
        cols: ['name', 'acc', 'accRate', 'cpacc', 'spend', 'leads']
    },
    {
        name: 'accepted90',
        label: 'Accepted 90',
        headings: ['acc90', 'acc90Rate', 'cpacc90'],
        cols: ['name', 'acc90', 'acc90Rate', 'cpacc90', 'spend', 'leads']
    },
    {
        name: 'accconf',
        label: 'Accepted Confirmed',
        headings: ['accconf', 'accconfRate', 'cpaccconf'],
        cols: ['name', 'accconf', 'accconfRate', 'cpaccconf', 'spend', 'leads']
    },
    {
        name: 'accconf120',
        label: 'Acc. Conf. 120',
        headings: ['accconf120', 'accconf120Rate', 'cpaccconf120'],
        cols: ['name', 'accconf120', 'accconf120Rate', 'cpaccconf120', 'spend', 'leads']
    },
    {
        name: 'starts',
        label: 'Starts',
        headings: ['start', 'startRate', 'cpstart'],
        cols: ['name', 'start', 'startRate', 'cpstart', 'spend', 'leads']
    },
    {
        name: 'pipeline',
        label: 'Pipeline',
        headings: ['contactRate', 'aipRate', 'comfileRate', 'accRate', 'accconfRate'],
        cols: ['name', 'leads', 'contact15Rate', 'appRate', 'comfileRate', 'accRate', 'accconfRate']
    },
    {
        name: 'pipelineLocked',
        label: 'Pipeline (Locked)',
        headings: ['contact15Rate', 'app30Rate', 'comfile60Rate', 'acc90Rate', 'accconf120Rate'],
        cols: ['name', 'leads', 'contact15Rate', 'app30Rate', 'comfile60Rate', 'acc90Rate', 'accconf120Rate']
    },
    {
        name: 'benchmarks',
        label: 'Benchmarks',
        headings: ['quality30Rate', 'cpquality30', 'startRate', 'cpstart'],
        cols: ['name', 'cpl', 'cpquality30', 'cpstart', 'contact15Rate', 'quality30Rate', 'startRate']
    },
]