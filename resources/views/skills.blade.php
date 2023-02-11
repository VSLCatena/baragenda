@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Skills</div>
                    <div class="card-body">
                        <h3>SelfSkills</h3>
                        @foreach($selfSkills as $k => $v)
                        <div class="row">
                            <div class="offset-1 col-8"   >
                                <p>{{ $v->name ?? '' }}</p>
                            </div>
                        </div>
                        @endforeach
                        <br><hr>
                        <h3>SelfCommitteeSkills</h3>
                        @foreach($selfCommitteeSkills as $k => $v)
                        <div class="row">
                            <div class="offset-1 col-8"   >
                                <p><b>{{ $v->name ?? '' }}</b>
                                    <br>
                                @foreach($v->skills as $kk => $vv)
                                    {{ $vv->name ?? '' }}
                                    <br>
                                @endforeach
                            </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
