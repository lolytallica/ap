<ul class="content-header-action pull-right">
    <li>
        <a href="#">
            <div class="badge-circle grd-green color-white"><i class="icofont-plus-sign"></i></div>
            <div class="action-text color-green">+{{Cache::get('numpayments')}}<span class="helper-font-small color-silver-dark">Payments</span></div>
        </a>
    </li>
    <li class="divider"></li>
    <li>
        <a href="#">
            <div class="badge-circle grd-teal color-white"><i class="icofont-user-md"></i></div>
            <div class="action-text color-teal">+{{Cache::get('nummerchantagreements')}}<span class="helper-font-small color-silver-dark">M.Agreements</span></div>
        </a>
    </li>
    <li class="divider"></li>
    <li>
        <a href="#">
            <div class="badge-circle grd-orange color-white">$</div>
            <div class="action-text color-orange">+{{Cache::get('numpaidinvoices')}} <span class="helper-font-small color-silver-dark">Invoices paid</span></div>
        </a>
    </li>
</ul>