@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="card">
                    <div class="card-header">Profielinstellingen</div>

                    <div class="card-block">
                        <form class="form-horizontal col-md-8 col-xs-12" method="POST">
                            {{ csrf_field() }}
                            <br>
							<div class="form-group">
                            <div class="row">
                                <div class="col-12"> <b>Wie ben ik?</b></div></div>
                            <div class="row">
                                <div class="col-12">Naam: {{ $info->name ?? '' }} </div></div>
                            <div class="row">
                                <div class="col-12">Lidnummer: {{ $info->lidnummer ?? '' }} </div></div>
                            <div class="row">
                                <div class="col-12">Relatienummer: {{ $info->relatienummer ?? '' }} </div></div><br>
                            <div class="row">
                                <div class="col-5"><b>Commissies: </b></div>
                            </div>
                            @foreach($committees as $k => $c)
                                <div class="row">
                                    <div class="offset-1 col-8"  data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="right" title="@foreach($c->infos as $u=>$i)<i>{{$i->name}}</i><br/>@endforeach" >
                                        <a class="" href="mailto:{{ $c->name ?? '' }}">{{ $c->name ?? '' }}
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                            <br>
                            <div class="row">
                                <div class="col-4"><b>Skills: </b></div>
                            </div>
                            @foreach($skills as $s => $skillinfo)
                                <div class="row">
                                    <div class="offset-1 col-8">{{ $skillinfo->name ?? '' }}  @if($skillinfo->committee->name) (<a class="" href="mailto:{{ $skillinfo->committee->name ?? '' }}">{{ $skillinfo->committee->name ?? '' }})</a> @endif
                                    </div>
                                </div>
                            @endforeach
                            <br>
							<div class="row">
                                <div class="col-12"><b>Voorkeuren</b></div></div>
                            <div class="row">
                                <div class="col-6">
                                    <label for="available">Open voor diensten</label>
                                </div>
                                <div class="col-6">
                                    <input id="available" name="available[]" type="checkbox" data-toggle="toggle" data-on="Ja" data-off="Nee"  data-onstyle="primary"   {{ $info->available == 1 ? 'checked' : '' }}  >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label for="autofill_name">Naam automatisch invullen</label>
                                </div>
                                <div class="col-6">
                                    <input id="autofill_name" name="autofill_name[]" type="checkbox" data-toggle="toggle" data-on="Ja" data-off="Nee"  data-onstyle="primary"   {{ $info->autofill_name == 1 ? 'checked' : '' }}  >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="extra_info">Extra informatie</label>
                                        <input id="extra_info" class="form-control" name="extra_info" value="{{ $info->extra_info }}">
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    Aanpassen
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
