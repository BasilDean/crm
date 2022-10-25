<section class="content-header">
    <h1>{{$title}}</h1>
</section>


<div class="content">
    <div class="box card">
        <form action="{{route('roles.update', ['role'=>$item->id])}}" role="form" enctype="multipart/form-data" method="post">

            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @csrf
                @method('PUT')

                <fieldset class="mb-3">
                    <legend class="">{{__('Common info')}}</legend>

                    <div class="form-group row">
                        <label for="title" class="col-for-label col-lg-2">
                            {{__('Title')}} <span class="text-danger">*</span>
                        </label>
                        <div class="col-lg-10">
                            <div class="input-group">
                                <input id="title" type="text" name="title" required value="{{$item->title ?? ""}}" placeholder="{{__('Title')}}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="alias" class="col-for-label col-lg-2">
                            {{__('Alias')}} <span class="text-danger">*</span>
                        </label>
                        <div class="col-lg-10">
                            <div class="input-group">
                                <input id="alias" type="text" name="alias" required value="{{$item->alias ?? ""}}" placeholder="{{__('Alias')}}" class="form-control">
                            </div>
                        </div>
                    </div>
                </fieldset>

                <button type="submit" class="btn btn-success">{{__('Submit')}}</button>

            </div>

        </form>
    </div>
</div>
