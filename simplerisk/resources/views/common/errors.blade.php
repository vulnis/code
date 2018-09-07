<!-- resources/views/common/errors.blade.php -->

@if (count($errors) > 0)
<div class="alert alert-danger m-1" role="alert">
    <div class="row">
        <div class="col-1">
            <strong><i class="fas fa-exclamation-triangle fa-fw fa-lg"></i></strong>
        </div>
        <div class="col-auto">
            @foreach ($errors->all() as $error)
                {{ $error }}
            @endforeach
        </div>
    </div>
</div>
@endif