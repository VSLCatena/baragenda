@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Skills</div>
                    <div class="card-body">
                        <h3>Mijn skills</h3>
                        @foreach($selfSkills as $k => $v)
                        <div class="row">
                            <div class="offset-1 col-8"   >
                                <p>{{ $v->name ?? '' }}</p>
                            </div>
                        </div>
                        @endforeach
                        <br><hr>
                        <h3>Skill van mijn commissie(s)</h3>
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
                        <h3>Iedereen</h3>
                        <div class="row">
                            <div class="offset-1 col-4">
                                <label for="exampleDataList" class="form-label">Datalist example</label>
                                <input class="form-control" list="datalistOptions" id="exampleDataList" placeholder="Zoeken..." data-bs-placement="bottom">
                                <datalist id="datalistOptions">
                                    @foreach($infos as $k => $v)
                                        <option value="{{ $v->name ?? '' }}"></option>
                                    @endforeach
                                </datalist>
                                <input type="checkbox" class="btn-check" id="btn-check" autocomplete="off">
                                <label class="btn btn-primary" for="btn-check">Single toggle</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
