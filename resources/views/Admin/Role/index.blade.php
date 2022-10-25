<section class="content-header">
    <h1>{{ $title }}</h1>
    <a href="{{route('roles.create')}}" class="btn btn-success">
        {{ __('Create role') }}
    </a>
</section>

<div class="content">
    <div class="card">
        <div class="table-responsive">
            @if($roles)
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Alias') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>

                    <tbdoy>
                        @foreach($roles as $role)
                            <tr>
                                <td>{{$role->id}}</td>
                                <td>{{$role->title}}</td>
                                <td>{{$role->alias}}</td>
                                <td>
                                    <a href="{{route('roles.edit', ['role'=>$role->id])}}" class="btn btn-primary">
                                        {{__('Edit')}}
                                    </a>

                                    <form action="{{route('roles.delete', ['role'=>$role->id])}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            {{__('Delete')}}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbdoy>
                </table>
            @endif
        </div>
    </div>
</div>
