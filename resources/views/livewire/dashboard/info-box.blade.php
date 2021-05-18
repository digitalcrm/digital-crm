<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-shopping-cart"></i></span>

            <div class="info-box-content">
                <a href="{{ route('products.index') }}">
                    <span class="info-box-text">{{ __('Products') }}</span>
                    <span class="info-box-number">
                        {{ $countProducts }}
                    </span>
                </a>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-address-book"
                    aria-hidden="true"></i></span>

            <div class="info-box-content">
                <a href="{{ route('companies.index') }}">
                    <span class="info-box-text">{{ __('Company') }}</span>
                    <span class="info-box-number">
                        {{ $countCompany }}
                    </span>
                </a>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>

            <div class="info-box-content">
                <a href="{{ url('leads/getproductleads/list') }}">
                    <span class="info-box-text">{{ __('Product Leads') }}</span>
                    <span class="info-box-number">
                        {{ $countProductLeads }}
                    </span>
                </a>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>

            <div class="info-box-content">
                <a href="{{ route('rfq-leads.index') }}">
                    <span class="info-box-text">{{ __('RFQ Leads') }}</span>
                    <span class="info-box-number">
                        {{ $countRfqLead }}
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>
