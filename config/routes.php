<?php
/**
 * TSILIZY CORE - Route Definitions
 * URL routing configuration
 */

return [
    // Authentication routes
    'login'  => ['controller' => 'AuthController', 'action' => 'login'],
    'logout' => ['controller' => 'AuthController', 'action' => 'logout'],
    
    // Dashboard
    ''          => ['controller' => 'DashboardController', 'action' => 'index'],
    'dashboard' => ['controller' => 'DashboardController', 'action' => 'index'],
    
    // Invoice Management
    'invoices'        => ['controller' => 'InvoiceController', 'action' => 'index'],
    'invoices/create' => ['controller' => 'InvoiceController', 'action' => 'create'],
    'invoices/store'  => ['controller' => 'InvoiceController', 'action' => 'store'],
    'invoices/edit'   => ['controller' => 'InvoiceController', 'action' => 'edit'],
    'invoices/update' => ['controller' => 'InvoiceController', 'action' => 'update'],
    'invoices/view'   => ['controller' => 'InvoiceController', 'action' => 'view'],
    'invoices/delete' => ['controller' => 'InvoiceController', 'action' => 'delete'],
    'invoices/archive' => ['controller' => 'InvoiceController', 'action' => 'archive'],
    'invoices/print'  => ['controller' => 'InvoiceController', 'action' => 'print'],
    'invoices/mark-paid' => ['controller' => 'InvoiceController', 'action' => 'markPaid'],
    'invoices/send' => ['controller' => 'InvoiceController', 'action' => 'send'],
    'invoices/print-list'  => ['controller' => 'InvoiceController', 'action' => 'printList'],
    
    // Client Management
    'clients'        => ['controller' => 'ClientController', 'action' => 'index'],
    'clients/create' => ['controller' => 'ClientController', 'action' => 'create'],
    'clients/store'  => ['controller' => 'ClientController', 'action' => 'store'],
    'clients/edit'   => ['controller' => 'ClientController', 'action' => 'edit'],
    'clients/update' => ['controller' => 'ClientController', 'action' => 'update'],
    'clients/view'   => ['controller' => 'ClientController', 'action' => 'view'],
    'clients/delete' => ['controller' => 'ClientController', 'action' => 'delete'],
    'clients/print'  => ['controller' => 'ClientController', 'action' => 'printList'],
    'clients/print-list'  => ['controller' => 'ClientController', 'action' => 'printList'],
    'clients/print-profile' => ['controller' => 'ClientController', 'action' => 'printProfile'],
    
    // Project Management
    'projects'        => ['controller' => 'ProjectController', 'action' => 'index'],
    'projects/create' => ['controller' => 'ProjectController', 'action' => 'create'],
    'projects/store'  => ['controller' => 'ProjectController', 'action' => 'store'],
    'projects/edit'   => ['controller' => 'ProjectController', 'action' => 'edit'],
    'projects/update' => ['controller' => 'ProjectController', 'action' => 'update'],
    'projects/view'   => ['controller' => 'ProjectController', 'action' => 'view'],
    'projects/delete' => ['controller' => 'ProjectController', 'action' => 'delete'],
    'projects/archive' => ['controller' => 'ProjectController', 'action' => 'archive'],
    'projects/print'  => ['controller' => 'ProjectController', 'action' => 'printDetails'], // Single project
    'projects/print-list'  => ['controller' => 'ProjectController', 'action' => 'printList'], // List
    'projects/print-details' => ['controller' => 'ProjectController', 'action' => 'printDetails'],
    
    // Tasks (nested under projects)
    'projects/tasks'        => ['controller' => 'ProjectController', 'action' => 'tasks'],
    'projects/tasks/store'  => ['controller' => 'ProjectController', 'action' => 'storeTask'],
    'projects/tasks/update' => ['controller' => 'ProjectController', 'action' => 'updateTask'],
    'projects/tasks/delete' => ['controller' => 'ProjectController', 'action' => 'deleteTask'],
    'projects/add-task'     => ['controller' => 'ProjectController', 'action' => 'storeTask'],
    'projects/toggle-task'  => ['controller' => 'ProjectController', 'action' => 'toggleTask'],
    
    // Milestones (nested under projects)
    'projects/milestones'        => ['controller' => 'ProjectController', 'action' => 'milestones'],
    'projects/milestones/store'  => ['controller' => 'ProjectController', 'action' => 'storeMilestone'],
    'projects/milestones/update' => ['controller' => 'ProjectController', 'action' => 'updateMilestone'],
    'projects/milestones/delete' => ['controller' => 'ProjectController', 'action' => 'deleteMilestone'],
    'projects/add-milestone'     => ['controller' => 'ProjectController', 'action' => 'storeMilestone'],
    
    // HR Management - Employees
    'hr'              => ['controller' => 'HRController', 'action' => 'index'],
    'hr/employees'    => ['controller' => 'HRController', 'action' => 'employees'],
    'hr/employees/create' => ['controller' => 'HRController', 'action' => 'createEmployee'],
    'hr/employees/store'  => ['controller' => 'HRController', 'action' => 'storeEmployee'],
    'hr/employees/edit'   => ['controller' => 'HRController', 'action' => 'editEmployee'],
    'hr/employees/update' => ['controller' => 'HRController', 'action' => 'updateEmployee'],
    'hr/employees/view'   => ['controller' => 'HRController', 'action' => 'viewEmployee'],
    'hr/employees/archive' => ['controller' => 'HRController', 'action' => 'archiveEmployee'],
    'hr/employees/print'  => ['controller' => 'HRController', 'action' => 'printEmployees'],
    'hr/employees/print-profile' => ['controller' => 'HRController', 'action' => 'printEmployeeProfile'],
    'hr/print-directory' => ['controller' => 'HRController', 'action' => 'printDirectory'],
    
    // HR Management - Paychecks
    'hr/paychecks'        => ['controller' => 'HRController', 'action' => 'paychecks'],
    'hr/paychecks/create' => ['controller' => 'HRController', 'action' => 'createPaycheck'],
    'hr/paychecks/store'  => ['controller' => 'HRController', 'action' => 'storePaycheck'],
    'hr/paychecks/edit'   => ['controller' => 'HRController', 'action' => 'editPaycheck'],
    'hr/paychecks/update' => ['controller' => 'HRController', 'action' => 'updatePaycheck'],
    'hr/paychecks/view'   => ['controller' => 'HRController', 'action' => 'viewPaycheck'],
    'hr/paychecks/print'  => ['controller' => 'HRController', 'action' => 'printPaycheck'], // Single paycheck
    'hr/paychecks/print-list' => ['controller' => 'HRController', 'action' => 'printPaychecks'], // List
    'hr/paychecks/process' => ['controller' => 'HRController', 'action' => 'processPaycheck'],
    'hr/paychecks/download' => ['controller' => 'HRController', 'action' => 'downloadPaycheck'],
    'hr/paychecks/resend' => ['controller' => 'HRController', 'action' => 'resendPaycheck'],
    
    // Contract Management
    'contracts'        => ['controller' => 'ContractController', 'action' => 'index'],
    'contracts/create' => ['controller' => 'ContractController', 'action' => 'create'],
    'contracts/store'  => ['controller' => 'ContractController', 'action' => 'store'],
    'contracts/edit'   => ['controller' => 'ContractController', 'action' => 'edit'],
    'contracts/update' => ['controller' => 'ContractController', 'action' => 'update'],
    'contracts/view'   => ['controller' => 'ContractController', 'action' => 'view'],
    'contracts/archive' => ['controller' => 'ContractController', 'action' => 'archive'],
    'contracts/print'  => ['controller' => 'ContractController', 'action' => 'printList'],
    'contracts/print-details' => ['controller' => 'ContractController', 'action' => 'printDetails'],
    
    // Partner Management
    'partners'        => ['controller' => 'PartnerController', 'action' => 'index'],
    'partners/create' => ['controller' => 'PartnerController', 'action' => 'create'],
    'partners/store'  => ['controller' => 'PartnerController', 'action' => 'store'],
    'partners/edit'   => ['controller' => 'PartnerController', 'action' => 'edit'],
    'partners/update' => ['controller' => 'PartnerController', 'action' => 'update'],
    'partners/view'   => ['controller' => 'PartnerController', 'action' => 'view'],
    'partners/archive' => ['controller' => 'PartnerController', 'action' => 'archive'],
    'partners/print'  => ['controller' => 'PartnerController', 'action' => 'printList'],
    'partners/print-list'  => ['controller' => 'PartnerController', 'action' => 'printList'],
    'partners/print-profile' => ['controller' => 'PartnerController', 'action' => 'printProfile'],
    
    // Company Configuration
    'company'          => ['controller' => 'CompanyController', 'action' => 'index'],
    'company/update'   => ['controller' => 'CompanyController', 'action' => 'update'],
    'company/update-profile' => ['controller' => 'CompanyController', 'action' => 'update'],
    'company/services' => ['controller' => 'CompanyController', 'action' => 'services'],
    'company/services/create' => ['controller' => 'CompanyController', 'action' => 'createService'],
    'company/services/store'  => ['controller' => 'CompanyController', 'action' => 'storeService'],
    'company/services/edit'   => ['controller' => 'CompanyController', 'action' => 'editService'],
    'company/services/update' => ['controller' => 'CompanyController', 'action' => 'updateService'],
    'company/services/delete' => ['controller' => 'CompanyController', 'action' => 'deleteService'],
    'company/services/print'  => ['controller' => 'CompanyController', 'action' => 'printServices'],
    'company/profile' => ['controller' => 'CompanyController', 'action' => 'profile'],
    'company/branding' => ['controller' => 'CompanyController', 'action' => 'branding'],
    'company/branding/update' => ['controller' => 'CompanyController', 'action' => 'updateBranding'],
    
    // Finance Management
    'finance'           => ['controller' => 'FinanceController', 'action' => 'index'],
    'finance/income'    => ['controller' => 'FinanceController', 'action' => 'income'],
    'finance/income/create' => ['controller' => 'FinanceController', 'action' => 'createIncome'],
    'finance/income/store'  => ['controller' => 'FinanceController', 'action' => 'storeIncome'],
    'finance/income/edit'   => ['controller' => 'FinanceController', 'action' => 'editIncome'],
    'finance/income/update' => ['controller' => 'FinanceController', 'action' => 'updateIncome'],
    'finance/income/delete' => ['controller' => 'FinanceController', 'action' => 'deleteIncome'],
    
    'finance/expenses'    => ['controller' => 'FinanceController', 'action' => 'expenses'],
    'finance/expenses/create' => ['controller' => 'FinanceController', 'action' => 'createExpense'],
    'finance/expenses/store'  => ['controller' => 'FinanceController', 'action' => 'storeExpense'],
    'finance/expenses/edit'   => ['controller' => 'FinanceController', 'action' => 'editExpense'],
    'finance/expenses/update' => ['controller' => 'FinanceController', 'action' => 'updateExpense'],
    'finance/expenses/delete' => ['controller' => 'FinanceController', 'action' => 'deleteExpense'],
    
    'finance/accounts'    => ['controller' => 'FinanceController', 'action' => 'accounts'],
    'finance/bank-accounts' => ['controller' => 'FinanceController', 'action' => 'accounts'], // Alias
    'finance/accounts/store'  => ['controller' => 'FinanceController', 'action' => 'storeAccount'],
    'finance/accounts/update' => ['controller' => 'FinanceController', 'action' => 'updateAccount'],
    'finance/accounts/delete' => ['controller' => 'FinanceController', 'action' => 'deleteAccount'],
    
    'finance/payment-methods' => ['controller' => 'FinanceController', 'action' => 'paymentMethods'],
    'finance/payment-methods/store'  => ['controller' => 'FinanceController', 'action' => 'storePaymentMethod'],
    'finance/payment-methods/update' => ['controller' => 'FinanceController', 'action' => 'updatePaymentMethod'],
    'finance/payment-methods/delete' => ['controller' => 'FinanceController', 'action' => 'deletePaymentMethod'],
    'finance/payment-methods/toggle' => ['controller' => 'FinanceController', 'action' => 'togglePaymentMethod'],
    
    'finance/reports'     => ['controller' => 'FinanceController', 'action' => 'reports'],
    'finance/reports/print' => ['controller' => 'FinanceController', 'action' => 'printReport'],
    'finance/reports/generate' => ['controller' => 'FinanceController', 'action' => 'index'], // Fallback or specific logic
    'finance/reports/export' => ['controller' => 'FinanceController', 'action' => 'exportReports'],
    
    // User Management (Admin only)
    'users'        => ['controller' => 'UserController', 'action' => 'index'],
    'users/create' => ['controller' => 'UserController', 'action' => 'create'],
    'users/store'  => ['controller' => 'UserController', 'action' => 'store'],
    'users/edit'   => ['controller' => 'UserController', 'action' => 'edit'],
    'users/update' => ['controller' => 'UserController', 'action' => 'update'],
    'users/delete' => ['controller' => 'UserController', 'action' => 'delete'],
    'users/toggle' => ['controller' => 'UserController', 'action' => 'toggle'],
    'users/profile' => ['controller' => 'UserController', 'action' => 'profile'],
    'users/profile/update' => ['controller' => 'UserController', 'action' => 'updateProfile'],
    'profile' => ['controller' => 'UserController', 'action' => 'profile'], // Alias for users/profile
    
    // Asset / Facilities Management
    'assets'        => ['controller' => 'AssetController', 'action' => 'index'],
    'assets/create' => ['controller' => 'AssetController', 'action' => 'create'],
    'assets/store'  => ['controller' => 'AssetController', 'action' => 'store'],
    'assets/edit'   => ['controller' => 'AssetController', 'action' => 'edit'],
    'assets/update' => ['controller' => 'AssetController', 'action' => 'update'],
    'assets/view'   => ['controller' => 'AssetController', 'action' => 'view'],
    'assets/delete' => ['controller' => 'AssetController', 'action' => 'delete'],
    'assets/assign' => ['controller' => 'AssetController', 'action' => 'assign'],
    'assets/print'  => ['controller' => 'AssetController', 'action' => 'printDetails'],
    'assets/print-list' => ['controller' => 'AssetController', 'action' => 'printList'],
    
    // Activity Logs
    'logs' => ['controller' => 'LogController', 'action' => 'index'],
    'logs/view' => ['controller' => 'LogController', 'action' => 'view'],
    'logs/clear' => ['controller' => 'LogController', 'action' => 'clear'],
    'logs/export' => ['controller' => 'LogController', 'action' => 'export'],
    
    // API endpoints (for AJAX)
    'api/dashboard/stats' => ['controller' => 'ApiController', 'action' => 'dashboardStats'],
    'api/finance/chart'   => ['controller' => 'ApiController', 'action' => 'financeChart'],
    'api/projects/chart'  => ['controller' => 'ApiController', 'action' => 'projectsChart'],
    'api/search'          => ['controller' => 'ApiController', 'action' => 'search'],
    'api/notifications'   => ['controller' => 'ApiController', 'action' => 'notifications'],
];
