/**
 * Metric Definitions
 */

export default {
    'name':  {
        name: 'name',
        heading: 'Program',
        type: 'base',
        object: '',
        location: 'name'
    },
    'ltv': {
        name: 'ltv',
        heading: 'LTV',
        type: 'pipeline', 
        location: 'ltv',
        description: 'Average Lifetime Value of a student'
    },
    // Percentage of goal to use for pace
    'complete': {
        name: 'complete',
        heading: 'Complete Ratio of Month',
        type: 'complete',
        location: 'complete'
    },
    // Lead Metrics
    'leads':  {
        name: 'leads',
        heading: 'Leads',
        type: 'base',
        location: 'actuals.leads',
        icon: 'users',
        popover: ['leadsBudgetPace', 'leadsBudgetPaceDelta']
    },
    'leadsProjection': {
        name: 'leadsProjection',
        heading: 'Leads Proj',
        type: 'projection',
        location: 'leads'
    },
    // Full Month Lead Goals
    'leadsBudget': {
        name: 'leadsBudget',
        heading: 'Lead Budget',
        type: 'base',
        location: 'budget.leads'
    },
    'leadsForecast': {
        name: 'leadsForecast',
        heading: 'Lead Forecast',
        type: 'base',
        location: 'forecast.leads',
    },
    // Variance: MTD Actuals to Full Month Budget
    'leadsBudgetDelta':  {
        name: 'leadsBudgetDelta',
        heading: 'Lead Budget &Delta;',
        type: 'delta',
        location: ['leads', 'leadsBudget'],
    },
    'leadsForecastDelta': {
        name: 'leadsForecastDelta',
        heading: 'Lead Forecast &Delta;',
        type: 'delta',
        location: ['leads', 'leadsForecast']
    },
    // Current MTD Goal Pace 
    // (where we should be to meet goal on a linear progression)
    'leadsBudgetPace':  {
        name: 'leadsBudgetPace',
        heading: 'Lead Bdgt Pace',
        type: 'pace',
        location: ['leadsBudget', 'complete'],
        description: 'Leads needed at the current point of the month to meet lead budget',
        popover: ['leadsBudget']
    },
    'leadsForecastPace': {
        name: 'leadsForecastPace',
        heading: 'Lead Fcst. Pace',
        type: 'pace',
        location: ['leadsForecast', 'complete'],
        description: 'Leads needed at the current point of the month to meet lead forecast'
    },
    // Variance: MTD Actuals to MTD Goals
    'leadsBudgetPaceDelta':  {
        name: 'leadsBudgetPaceDelta',
        heading: 'Lead Bdgt Pace &Delta;',
        type: 'delta',
        location: ['leads', 'leadsBudgetPace'],
        popover: ['leadsBudget', 'leadsBudgetPacePercent']
    },
    'leadsForecastPaceDelta': {
        name: 'leadsForecastPaceDelta',
        heading: 'Lead Fcst &Delta;',
        type: 'delta',
        location: ['leads', 'leadsForecastPace']
    },
    // Percent to lead goal pace
    'leadsBudgetPacePercent': {
        name: 'leadsBudgetPacePercent',
        heading: 'Lead Bdgt Pace %',
        type: 'ratio',
        location: ['leads', 'leadsBudgetPace']
    },
    'leadsForecastPacePercent': {
        name: 'leadsForecastPacePercent',
        heading: 'Lead Fcst %',
        type: 'ratio',
        location: ['leads', 'leadsForecastPace']
    },
    // Variance of lead forecast to budget
    'leadBudgetForecastDelta': {
        name: 'leadBudgetForecastDelta',
        heading: 'Budget - Fcst',
        type: 'delta',
        location: ['leadsBudget', 'leadsForecast']
    },
    // Percent to Lead Goals
    'leadsBudgetPercent': {
        name: 'leadsBudgetPercent',
        heading: 'Lead Budget %',
        type: 'ratio',
        location: ['leads', 'leadsBudget']
    },
    'leadsForecastPercent': {
        name: 'leadsForecastPercent',
        heading: 'Lead Forecast %',
        type: 'ratio',
        location: ['leads', 'leadsForecast']
    },
    
    // Spend Metrics
    'spend': {
        name: 'spend',
        heading: 'Spend',
        type: 'base',
        location: 'actuals.spend',
        icon: 'money-bill-alt',
        popover: ['spendBudget', 'spendBudgetDelta']
    },
    // Full Month Lead Goals
    'spendBudget': {
        name: 'spendBudget',
        heading: 'Spend Budget',
        type: 'base',
        location: 'budget.spend'
    },
    'spendForecast': {
        name: 'spendForecast',
        heading: 'Spend Forecast',
        type: 'base',
        location: 'forecast.spend',
    },
    'spendProjection': {
        name: 'spendProjection',
        heading: 'Spend Proj',
        type: 'projection',
        location: 'spend'
    },
    // Variance to Spend Goals
    'spendBudgetDelta':  {
        name: 'spendBudgetDelta',
        heading: 'Spend Bdgt &Delta;',
        type: 'delta',
        location: ['spend', 'spendBudget'],
        popover: ['spendBudget', 'spendBudgetPercent']
    },
    'spendForecastDelta':  {
        name: 'spendForecastDelta',
        heading: 'Spend Fcst &Delta;',
        type: 'delta',
        location: ['spend', 'spendForecast']
    },
    // Percent to Spend Goals
    'spendBudgetPercent':  {
        name: 'spendBudgetPercent',
        heading: 'Spend Budget %',
        type: 'ratio',
        location: ['spend', 'spendBudget']
    },
    'spendForecastPercent':  {
        name: 'spendForecastPercent',
        heading: 'Spend Forecast %',
        type: 'ratio',
        location: ['spend', 'spendForecast']
    },
    
    // Cost Per Lead Metrics
    'cpl':  {
        name: 'cpl',
        heading: 'CPL',
        type: 'ratio',
        location: ['spend', 'leads'],
        icon: 'balance-scale',
        popover: ['cplBudget', 'cplBudgetDelta']
    },
    'cplBudget':  {
        name: 'cplBudget',
        heading: 'CPL Budget',
        type: 'ratio',
        location: ['spendBudget', 'leadsBudget']
    },
    'cplBudgetPercent': {
        name: 'cplBudgetPercent',
        heading: 'CPL Budget %',
        type: 'ratio',
        location: ['cpl', 'cplBudget']
    },
    'cplForecast':  {
        name: 'cplForecast',
        heading: 'CPL Forecast',
        type: 'ratio',
        location: ['spendForecast', 'leadsForecast']
    },
    'cplProjection': {
        name: 'cplProjection',
        heading: 'CPL Proj',
        type: 'projection',
        location: 'cpl'
    },
    // Variance to Goals
    'cplBudgetDelta':  {
        name: 'cplBudgetDelta',
        heading: 'CPL Budget &Delta;',
        type: 'delta',
        location: ['cpl', 'cplBudget'],
        popover: ['cplBudget', 'cplBudgetPercent']
    },
    'cplForecastDelta':  {
        name: 'cplForecastDelta',
        heading: 'CPL Forecast &Delta;',
        type: 'delta',
        location: ['cpl', 'cplForecast']
    },

    // In Sales
    'insales': {
        name: 'insales',
        heading: 'In Sales',
        type: 'base',
        location: 'pipeline.insales',
        icon: 'users',
        // popover: ['leadsBudgetPace', 'leadsBudgetPaceDelta']
    },
    
    // Leads Contacted
    'contact': {
        name: 'contact',
        heading: 'Contact',
        type: 'base',
        location: 'pipeline.contact',
        icon: 'phone',
        popover: ['contactRate', 'cpcontact']
    },
    'cpcontact': {
        name: 'cpcontact',
        heading: 'CPC',
        type: 'ratio',
        location: ['spend', 'contact']
    },
    'contactRate': {
        name: 'contactRate',
        heading: 'Contact %',
        type: 'ratio',
        location: ['contact', 'leads']
    },
    // Contact 15
    'contact15': {
        name: 'contact15',
        heading: 'Contact 15',
        type: 'base',
        location: 'pipeline.contact15',
        icon: 'phone',
        popover: ['contact15Rate', 'cpcontact15']
    },  
    'cpcontact15': {
        name: 'cpcontact15',
        heading: 'CPC 15',
        type: 'ratio',
        location: ['spend', 'contact15']
    },
    'contact15Rate': {
        name: 'contact15Rate',
        heading: 'Contact 15%',
        type: 'ratio',
        location: ['contact15', 'leads'],
        popover: ['contact15']
    },
    // Quality Leads
    'quality': {
        name: 'quality',
        heading: 'Quality',
        type: 'base',
        location: 'pipeline.quality',
        icon: 'gem',
        popover: ['qualityRate', 'cpquality']
    },
    'cpquality': {
        name: 'cpquality',
        heading: 'CPQL',
        type: 'ratio',
        location: ['spend', 'quality'],
        popover: ['quality', 'spend']
    }, 
    'qualityRate': {
        name: 'qualityRate',
        heading: 'Quality %',
        type: 'ratio',
        location: ['quality', 'leads'],
    },
    // Quality30
    'quality30': {
        name: 'quality30',
        heading: 'Quality 30',
        type: 'base',
        location: 'pipeline.quality30',
        icon: 'gem',
        popover: ['quality30Rate', 'cpquality30']
    },
    'cpquality30': {
        name: 'cpquality30',
        heading: 'CPQL30',
        type: 'ratio',
        location: ['spend', 'quality30'],
        popover: ['quality30', 'spend']
    },
    'quality30Rate': {
        name: 'quality30Rate',
        heading: 'Quality 30%',
        type: 'ratio',
        location: ['quality30', 'leads'],
        popover: ['quality30']
    },

    // Applications in Progress
    'aip': {
        name: 'aip',
        heading: 'AIP',
        type: 'base',
        location: 'pipeline.aip'
    },
    'cpaip': {
        name: 'cpaip',
        heading: '$ / AIP',
        type: 'ratio',
        location: ['spend', 'aip']
    },
    'aipRate': {
        name: 'aipRate',
        heading: 'AIP %',
        type: 'ratio',
        location: ['aip', 'leads']
    },
    // Applications received within 30 days of lead date
    'app30': {
        name: 'app30',
        heading: 'App 30',
        type: 'base',
        location: 'pipeline.app30'
    },
    'cpapp30': {
        name: 'cpapp30',
        heading: '$ / App30',
        type: 'ratio',
        location: ['spend', 'app30']
    },
    'app30Rate': {
        name: 'app30Rate',
        heading: 'App 30%',
        type: 'ratio',
        location: ['app30', 'leads']
    }, 

    // Complete File Submitted
    'comfile': {
        name: 'comfile',
        heading: 'Com File',
        type: 'base',
        location: 'pipeline.comfile'
    }, 
    'cpcomfile': {
        name: 'cpcomfile',
        heading: '$ / Com File',
        type: 'ratio',
        location: ['spend', 'comfile']
    },
    'comfileRate': {
        name: 'comfileRate',
        heading: 'Com File %',
        type: 'ratio',
        location: ['comfile', 'leads']
    },

    // Complete File within 60 days of lead date
    'comfile60': {
        name: 'comfile60',
        heading: 'Com File 60',
        type: 'base',
        location: 'pipeline.comfile60'
    },
    'cpcomfile60': {
        name: 'cpcomfile60',
        heading: '$ / Com File 60',
        type: 'ratio',
        location: ['spend', 'comfile60']
    },
    'comfile60Rate': {
        name: 'comfile60Rate',
        heading: 'Com File 60%',
        type: 'ratio',
        location: ['comfile60', 'leads']
    }, 

    // Accepted into the program
    'acc': {
        name: 'acc',
        heading: 'Acc',
        type: 'base',
        location: 'pipeline.acc'
    },
    'cpacc': {
        name: 'cpacc',
        heading: '$ / Acc',
        type: 'ratio',
        location: ['spend', 'acc']
    },
    'accRate': {
        name: 'accRate',
        heading: 'Acc %',
        type: 'ratio',
        location: ['acc', 'leads']
    },
    // Accepted within 90 days of lead date
    'acc90': {
        name: 'acc90',
        heading: 'Acc 90',
        type: 'base',
        location: 'pipeline.acc90'
    },
    'cpacc90': {
        name: 'cpacc90',
        heading: '$ / Acc90',
        type: 'ratio',
        location: ['spend', 'acc90']
    },
    'acc90Rate': {
        name: 'acc90Rate',
        heading: 'Acc 90%',
        type: 'ratio',
        location: ['acc90', 'leads']
    }, 

    // Accepted & Confirmed
    'accconf': {
        name: 'accconf',
        heading: 'Acc Conf',
        type: 'base',
        location: 'pipeline.accconf'
    },
    'cpaccconf': {
        name: 'cpaccconf',
        heading: '$ / accconf',
        type: 'ratio',
        location: ['spend', 'accconf']
    },
    'accconfRate': {
        name: 'accconfRate',
        heading: 'Acc Conf %',
        type: 'ratio',
        location: ['accconf', 'leads']
    },

    // Accepted & Confirmed Within 120 days of lead date
    'accconf120': {
        name: 'accconf120',
        heading: 'Acc Conf 120',
        type: 'base',
        location: 'pipeline.accconf120'
    },
    'cpaccconf120': {
        name: 'cpaccconf120',
        heading: '$ / accconf120',
        type: 'ratio',
        location: ['spend', 'accconf120']
    },
    'accconf120Rate': {
        name: 'accconf120Rate',
        heading: 'Acc Conf 120%',
        type: 'ratio',
        location: ['accconf120', 'leads']
    }, 

    'start': {
        name: 'start',
        heading: 'Starts',
        type: 'base',
        location: 'pipeline.startsleadmonth'
    },
    'cpstart': {
        name: 'cpstart',
        heading: 'CPS',
        type: 'ratio',
        location: ['spend', 'start']
    },
    'startRate': {
        name: 'startRate',
        heading: 'CVRS',
        type: 'ratio',
        location: ['start', 'leads']
    },

    'starts': {
        name: 'starts',
        heading: 'Starts',
        type: 'revenue',
        location: 'starts',
        icon: 'flag-checkered',
        popover: ['startsBudget', 'startsBudgetDelta']
    },
    'startsBudget': {
        name: 'startsBudget',
        heading: 'Starts Budgeted',
        type: 'revenue',
        location: 'startsBudget'
    },
    'startsBudgetDelta': {
        name: 'startsBudgetDelta',
        heading: 'Starts Plan &Delta;',
        type: 'revenue',
        location: ['starts', 'startsBudget'],
        popover: ['startsAchieved']
    },
    'startsAchieved': {
        name: 'startsAchieved',
        heading: 'Starts Plan %',
        type: 'revenue',
        location: 'startsAchieved'
    },

    'students': {
        name: 'students',
        heading: 'Students',
        type: 'revenue',
        location: 'students',
        icon: 'user-graduate',
        popover: ['studentsBudget', 'studentsBudgetDelta']
    },
    'studentsBudget': {
        name: 'studentsBudget',
        heading: 'Students Budgeted',
        type: 'revenue',
        location: 'studentsBudget'
    },
    'studentsBudgetDelta': {
        name: 'studentsBudgetDelta',
        heading: 'Students Plan &Delta;',
        type: 'revenue',
        location: ['students', 'studentsBudget'],
        popover: ['studentsAchieved']
    },
    'studentsAchieved': {
        name: 'studentsAchieved',
        heading: 'Students Plan %',
        type: 'revenue',
        location: 'studentsAchieved'
    },

    'revenue': {
        name: 'revenue',
        heading: 'Revenue',
        type: 'revenue',
        location: 'revenue',
        icon: 'money-bill-wave',
        popover: ['revenueBudget', 'revenueBudgetDelta']
    },
    'revenueBudget': {
        name: 'revenueBudget',
        heading: 'Revenue Budgeted',
        type: 'revenue',
        location: 'revenueBudget'
    },
    'revenueBudgetDelta': {
        name: 'revenueBudgetDelta',
        heading: 'Revenue Plan &Delta;',
        type: 'revenue',
        location: ['revenue', 'revenueBudget'],
        popover: ['revenueAchieved']
    },
    'revenueAchieved': {
        name: 'revenueAchieved',
        heading: 'Revenue Plan %',
        type: 'revenue',
        location: 'revenueAchieved'
    },

    'inSalesToAppRate': {
        name: 'inSalesToAppRate',
        heading: 'InSales - App %',
        type: 'ratio',  
        location: ['aip', 'insales'],
        popover: ['aip', 'insales'],
    },
}