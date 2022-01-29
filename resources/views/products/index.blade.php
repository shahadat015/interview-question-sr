@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Products') }}</h1>
    </div>


    <div class="card">
        <form action="{{ route('product.index') }}" method="get" class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" placeholder="Product Title" class="form-control" value="{{ request('title') }}">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="" class="form-control">
                        <option value="">{{ __('Choose') }}</option>
                        @foreach($variants as $variant)
                            <option value="{{ $variant->variant }}" {{ request('variant') == $variant->variant ? 'selected' : '' }}>{{ ucfirst($variant->variant) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">{{ __('Price Range') }}</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control" value="{{ request('price_from') }}">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control" value="{{ request('price_to') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->title }} <br> {{ __('Created at') }} : {{ $product->created_at->format('d-M-Y') }}</td>
                                <td width="30%">{{ Str::limit($product->description, 200) }}</td>
                                <td>
                                    <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant">
                                        @foreach($product->variantPrices as $variantPrice)
                                            <dt class="col-sm-3 pb-0">
                                                {{ $product->generateVariant($variantPrice)  }}
                                            </dt>
                                            <dd class="col-sm-9">
                                                <dl class="row mb-0">
                                                    <dt class="col-sm-4 pb-0">{{ __('Price') }} : {{ $variantPrice->price }}</dt>
                                                    <dd class="col-sm-8 pb-0">{{ __('InStock') }} : {{ $variantPrice->stock }}</dd>
                                                </dl>
                                            </dd>
                                        @endforeach
                                    </dl>
                                    <button onclick="$('#variant').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('product.edit', $product->id) }}" class="btn btn-success">{{ __('Edit') }}</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="text-center font-weight-bold">
                                <td colspan="6">{{ __('No product found') }}</td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <p>{{ __('Showing') }} {{ $products->firstItem() }} {{ __('to') }} {{ $products->lastItem() }} {{ __('out of') }} {{ $products->total() }}</p>
                </div>
                <div class="col-md-4">
                    {{ $products->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection
