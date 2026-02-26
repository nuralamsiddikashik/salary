<aside class="w-64 bg-indigo-900 text-white hidden md:flex flex-col">
    <div class="p-6 text-2xl font-bold border-b border-indigo-800">
        MyDash
    </div>
    <nav class="flex-1 px-4 py-6 space-y-2">
        <a href="#" class="flex items-center p-3 bg-indigo-800 rounded-lg">
            <i class="fas fa-home mr-3"></i> Dashboard
        </a>
        <a href="{{ route('employee.create') }}" class="flex items-center p-3 hover:bg-indigo-800 rounded-lg transition">
            <i class="fas fa-users mr-3"></i> Employees
        </a>
        <a href="{{ route('loan.create') }}" class="flex items-center p-3 hover:bg-indigo-800 rounded-lg transition">
            <i class="fas fa-chart-line mr-3"></i> Loan
        </a>
        <a href="{{ route('payroll.create') }}" class="flex items-center p-3 hover:bg-indigo-800 rounded-lg transition">
            <i class="fas fa-file-invoice-dollar mr-3"></i> Payroll
        </a>

        <a href="{{ route('report.index') }}" class="flex items-center p-3 hover:bg-indigo-800 rounded-lg transition">
            <i class="fas fa-file-alt mr-3"></i> Reports
        </a>
       
    </nav>
    <div class="p-4 border-t border-indigo-800">
        <button class="flex items-center text-gray-300 hover:text-white">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </button>
    </div>
</aside>