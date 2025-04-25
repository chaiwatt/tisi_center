@extends('layouts.app')

@push('css')
    <!-- ===== Parsley js ===== -->
    <link href="{{asset('plugins/components/parsleyjs/parsley.css')}}" rel="stylesheet" />
@endpush

@section('content')

    @if(array_key_exists('format_result', $data))

        {!! Form::open(['class' => 'form-horizontal']) !!}

            <div class="row m-t-20">
                <div class="col-md-4">

                </div>
                <div class="col-md-4">

                    @php
                        $detail = array_key_exists('format_result_detail', $data) ? $data['format_result_detail'] : [] ;
                    @endphp

                    @if ($data['format_result']=='integer')

                        {!! integer($detail)['html'] !!}

                    @elseif ($data['format_result']=='integer_range')

                        {!! integer_range($detail)['html'] !!}

                    @elseif ($data['format_result']=='decimal')

                        {!! decimal($detail)['html'] !!}

                    @elseif ($data['format_result']=='decimal_range')

                        {!! decimal_range($detail)['html'] !!}

                    @elseif ($data['format_result']=='select')

                        {!! select($detail)['html'] !!}

                    @elseif ($data['format_result']=='select_multiple')

                        @php
                            $result = select_multiple($detail);
                        @endphp

                        {!! $result['html'] !!}
                        @push('js')
                            <script>
                                {!! $result['js'] !!}
                            </script>
                        @endpush

                    @elseif ($data['format_result']=='text')

                        {{ text($detail)['html'] }}

                    @elseif ($data['format_result']=='mix')

                        @php
                            $inputs = [];
                            if(is_array($detail)){
                                foreach ($detail as $key => $item){
                                    $function_name = $item['format_result_mix'];
                                    $inputs[] = $function_name($item);
                                }
                            }
                        @endphp

                        {!! implode('', collect($inputs)->pluck('html')->toArray()) !!}

                        @push('js')
                            <script>
                                {!! implode('', collect($inputs)->pluck('js')->toArray()) !!}
                            </script>
                        @endpush

                    @endif

                    <div class="m-t-10">
                        <button type="submit" class="btn btn-success">บันทึก</button>
                    </div>

                </div>

            </div>

        {!! Form::close() !!}
    @else
        ไม่พบประเภท input
    @endif

    @php

        function integer($detail){

            $attribute = ['class' => 'form-control'];
            if(array_key_exists('min', $detail) && is_numeric($detail['min'])){
                $attribute['min'] = $detail['min'];
            }
            if(array_key_exists('max', $detail) && is_numeric($detail['max'])){
                $attribute['max'] = $detail['max'];
            }

            $result['html']  = '<div class="input-group">';
            $result['html'] .=     Form::number('result', null, $attribute);
            $result['html'] .=     array_key_exists('unit', $detail) && !is_null($detail['unit']) ? '<span class="input-group-addon">'.$detail['unit'].'</span>' : '' ;
            $result['html'] .= '</div>';

            $result['js'] = '';
            return $result;
        }

        function integer_range($detail){

            $attribute_start = $attribute_end = ['class' => 'form-control'];

            if(array_key_exists('min_start', $detail) && is_numeric($detail['min_start'])){
                $attribute_start['min'] = $detail['min_start'];
            }
            if(array_key_exists('max_start', $detail) && is_numeric($detail['max_start'])){
                $attribute_start['max'] = $detail['max_start'];
            }

            if(array_key_exists('min_end', $detail) && is_numeric($detail['min_end'])){
                $attribute_end['min'] = $detail['min_end'];
            }
            if(array_key_exists('max_end', $detail) && is_numeric($detail['max_end'])){
                $attribute_end['max'] = $detail['max_end'];
            }

            $result['html']  = '<div class="input-group">';
            $result['html'] .=    Form::number('result_start', null, $attribute_start);
            $result['html'] .=    '<span class="input-group-addon">ถึง</span>';
            $result['html'] .=    Form::number('result_end', null, $attribute_end);
            $result['html'] .=    array_key_exists('unit', $detail) && !is_null($detail['unit']) ? '<span class="input-group-addon">'.$detail['unit'].'</span>' : '' ;
            $result['html'] .= '</div>';

            $result['js'] = '';
            return $result;

        }

        function decimal($detail){

            $attribute = ['class' => 'form-control'];
            if(array_key_exists('min', $detail) && is_numeric($detail['min'])){
                $attribute['min'] = $detail['min'];
            }
            if(array_key_exists('max', $detail) && is_numeric($detail['max'])){
                $attribute['max'] = $detail['max'];
            }
            if(array_key_exists('digit', $detail) && is_numeric($detail['digit'])){
                $attribute['step'] = '0.'.str_pad('1', $detail['digit'], '0', STR_PAD_LEFT);
            }

            $result['html']  = '<div class="input-group">';
            $result['html'] .=     Form::number('result', null, $attribute);
            $result['html'] .=     array_key_exists('unit', $detail) && !is_null($detail['unit']) ? '<span class="input-group-addon">'.$detail['unit'].'</span>' : '' ;
            $result['html'] .= '</div>';

            $result['js'] = '';
            return $result;
        }

        function decimal_range($detail){
            $attribute_start = $attribute_end = ['class' => 'form-control'];

            if(array_key_exists('min_start', $detail) && is_numeric($detail['min_start'])){
                $attribute_start['min'] = $detail['min_start'];
            }
            if(array_key_exists('max_start', $detail) && is_numeric($detail['max_start'])){
                $attribute_start['max'] = $detail['max_start'];
            }

            if(array_key_exists('min_end', $detail) && is_numeric($detail['min_end'])){
                $attribute_end['min'] = $detail['min_end'];
            }
            if(array_key_exists('max_end', $detail) && is_numeric($detail['max_end'])){
                $attribute_end['max'] = $detail['max_end'];
            }
            if(array_key_exists('digit', $detail) && is_numeric($detail['digit'])){
                $attribute_start['step'] = $attribute_end['step'] = '0.'.str_pad('1', $detail['digit'], '0', STR_PAD_LEFT);
            }

            $result['html']  = '<div class="input-group">';
            $result['html'] .=     Form::number('result_start', null, $attribute_start);
            $result['html'] .=     '<span class="input-group-addon">ถึง</span>';
            $result['html'] .=     Form::number('result_end', null, $attribute_end);
            $result['html'] .=     array_key_exists('unit', $detail) && !is_null($detail['unit']) ? '<span class="input-group-addon">'.$detail['unit'].'</span>' : '' ;
            $result['html'] .= '</div>';

            $result['js']   = '';
            return $result;
        }

        function select($detail){

            $options = array_key_exists('option_list', $detail) ? explode(',', $detail['option_list']) : [] ;
            $options = array_combine($options, $options); //copy value to key
            $options = array_key_exists('option_blank', $detail) ? ['' => $detail['option_blank']]+$options : $options ;//เติมตัวเลือกว่างไว้อันแรก

            $result['html'] = Form::select('result', $options, null, ['class' => 'form-control']);
            $result['js']   = '';
            return $result;
        }

        function select_multiple($detail){
            $options        = array_key_exists('option_list', $detail) ? explode(',', $detail['option_list']) : [] ;
            $options        = array_combine($options, $options); //copy value to key
            $result['html'] = Form::select('result', $options, null, ['multiple' => true]);

            $result['js']  = '$(document).ready(function() {
                                $(\'select[name="result"]\').select2({';
            $result['js'] .=        array_key_exists('option_blank', $detail) ? 'placeholder: "'.$detail['option_blank'] . '",' : '' ;
            $result['js'] .=        array_key_exists('select_limit', $detail) && is_numeric($detail['select_limit']) ? 'maximumSelectionSize: '.$detail['select_limit'] : '' ;
            $result['js'] .=    '});';
            $result['js'] .=  '});';

            return $result;
        }

        function text($detail){

            $attribute = ['class' => 'form-control'];

            if(array_key_exists('placeholder', $detail) && $detail['placeholder']!=''){
                $attribute['placeholder'] = $detail['placeholder'];
            }

            $result['html'] = Form::text('result', null, $attribute);
            $result['js']   = '';
            return $result;
        }

        function label($detail){
            $result['html'] = '<label class="control-label text-right">'.$detail['label'].'</label>';
            $result['js']   = '';
            return $result;
        }

    @endphp

@endsection

@push('js')

    <!-- ===== PARSLEY JS Validation ===== -->
    <script src="{{asset('plugins/components/parsleyjs/parsley.min.js')}}"></script>
    <script src="{{asset('plugins/components/parsleyjs/language/th.js')}}"></script>

    <script>

        $(document).ready(function() {
            if($('form').length>0){
                $('form:first:not(.not_validated)').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
                })
                .on('form:submit', function() {
                return true; // Don't submit form for this demo
                });
            }
        });

    </script>
@endpush
