@extends('layouts.main')
@section('content')
        <div class="row">
            <div class="col-md-4">
              <div class="card" style="margin-bottom:1rem">
                <div class="card-header">
                  <h3>Categories</h3>
                </div>
                <div class="card-body">
                  <ul class="list-group">
                    @foreach ($categories as $category)
                      <li class="list-group-item">
                        <div class="d-flex justify-content-between">
                          {{ $category->name }}
                          <div class="button-group d-flex">
                            <a  class="btn btn-outline-danger btn-sm" href="{{ route('category.destroy', $category) }}" data-confirm="Are you sure? It will also delete nested categories (if they exist)!" data-method="delete" rel="nofollow">Delete</a>
                          </div>
                        </div>

                        @if ($category->children)
                          <ul class="list-group mt-2">
                            @foreach ($category->children as $child)
                              <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                  {{ $child->name }}

                                  <div class="button-group d-flex">
                                    <a  class="btn btn-outline-danger btn-sm" href="{{ route('category.destroy', $child) }}" data-confirm="Are you sure? It will also delete nested categories (if they exist)!" data-method="delete" rel="nofollow">Delete</a>
                                  </div>
                                </div>

                                @if ($child->children)
                                    <ul class="list-group mt-2">
                                        @foreach ($child->children as $grandchild)
                                            <li class="list-group-item">
                                                <div class="d-flex justify-content-between">
                                                    {{ $grandchild->name }}

                                                    <div class="button-group d-flex">
                                                    <a  class="btn btn-outline-danger btn-sm" href="{{ route('category.destroy', $grandchild) }}" data-confirm="Are you sure?" data-method="delete" rel="nofollow">Delete</a>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                 @endif
                          
                              </li>
                            @endforeach
                          </ul>
                        @endif
                      </li>
                    @endforeach
                  </ul>
                </div>
              </div>
            </div>
            @auth
              @include('categories.create')          
            @endauth
@endsection