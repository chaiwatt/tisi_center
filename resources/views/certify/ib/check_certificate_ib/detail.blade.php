@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">คำขอรับใบรับรองหน่วยตรวจ landing {{ $certi_ib->app_no ?? null }} </h3>
                    @can('view-'.str_slug('checkcertificateib'))
                        <a class="btn btn-success pull-right" href="{{ url("$previousUrl") }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
                    {!! Form::model($certi_ib, [
                        'id'=>'app_certi_form',
                        'class' => 'form-horizontal'
                    ]) !!}
                      <div id="box-readonly">
                        @include ('certify/ib/check_certificate_ib/form.form01')
                        @include ('certify/ib/check_certificate_ib/form.form02')
                        @include ('certify/ib/check_certificate_ib/form.form03')
                        @include ('certify/ib/check_certificate_ib/form.form04')
                        @include ('certify/ib/check_certificate_ib/form.form05')
                        @include ('certify/ib/check_certificate_ib/form.form06')
                        @include ('certify/ib/check_certificate_ib/form.form07')
                        @include ('certify/ib/check_certificate_ib/form.form08')
                        @include ('certify/ib/check_certificate_ib/form.form09')
                        @include ('certify/ib/check_certificate_ib/form.form10')
                      </div>
                   
                        <div class="row form-group">
                            <a  href="{{ url("$previousUrl") }}">
                                <div class="alert alert-dark text-center" role="alert">
                                    <b>กลับ</b>
                                </div>
                            </a> 
                        </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection
@push('js') 
    <script>
        jQuery(document).ready(function() {
              $('#box-readonly').find('button[type="submit"]').remove();
              $('#box-readonly').find('.icon-close').parent().remove();
              $('#box-readonly').find('.fa-copy').parent().remove();
              $('#box-readonly').find('.hide_attach').hide();
              $('#box-readonly').find('input').prop('disabled', true);
              $('#box-readonly').find('input').prop('disabled', true);
              $('#box-readonly').find('textarea').prop('disabled', true); 
              $('#box-readonly').find('select').prop('disabled', true);
              $('#box-readonly').find('.bootstrap-tagsinput').prop('disabled', true);
              $('#box-readonly').find('span.tag').children('span[data-role="remove"]').remove();
        });

        function renderInitialTable() {
            const ibScopeTransactions = @json($ibScopeTransactions ?? []);

            console.log(ibScopeTransactions)

            transactions = ibScopeTransactions.map(item => ({
                ib_main_category_scope_id: item.ib_main_category_scope_id,
                ib_main_category_scope_text: item.ib_main_category_scope ? item.ib_main_category_scope.name : '',
                ib_sub_category_scope_id: item.ib_sub_category_scope_id,
                ib_sub_category_scope_text: item.ib_sub_category_scope ? item.ib_sub_category_scope.name : '',
                ib_scope_topic_id: item.ib_scope_topic_id,
                ib_scope_topic_text: item.ib_scope_topic ? item.ib_scope_topic.name : '',
                ib_scope_detail_id: item.ib_scope_detail_id,
                ib_scope_detail_text: item.ib_scope_detail ? item.ib_scope_detail.name : '',
                standard: item.standard || '',
                standard_en: item.standard_en || ''
            }));

            const groupedArray = groupTransactions(transactions);
            renderIbScopeTable(groupedArray);
            console.log('server transactions', transactions);
        }

        renderInitialTable();

        function groupTransactions(transactions) {
            // จัดกลุ่มตาม mainCategoryText
            const groupedTransactions = transactions.reduce((acc, transaction) => {
                const key = transaction.ib_main_category_scope_text;
                if (!acc[key]) {
                    acc[key] = [];
                }
                acc[key].push(transaction);
                return acc;
            }, {});

            // แปลงเป็น array และจัดกลุ่มย่อยตาม subCategoryText และ scopeTopicText
            const groupedArray = Object.entries(groupedTransactions).map(([mainCategoryText, transactions]) => {
                const subGrouped = transactions.reduce((acc, transaction) => {
                    const subKey = transaction.ib_sub_category_scope_text;
                    if (!acc[subKey]) {
                        acc[subKey] = [];
                    }
                    acc[subKey].push(transaction);
                    return acc;
                }, {});

                const subCategories = Object.entries(subGrouped).map(([subCategoryText, transactions]) => {
                    const topicGrouped = transactions.reduce((acc, transaction) => {
                        const topicKey = transaction.ib_scope_topic_text;
                        if (!acc[topicKey]) {
                            acc[topicKey] = [];
                        }
                        acc[topicKey].push(transaction);
                        return acc;
                    }, {});

                    return {
                        subCategoryText,
                        scopeTopics: Object.entries(topicGrouped).map(([scopeTopicText, transactions]) => ({
                            scopeTopicText,
                            transactions
                        }))
                    };
                });

                return {
                    mainCategoryText,
                    subCategories
                };
            });

            return groupedArray;
        }

        function renderIbScopeTable(groupedArray) {
            // ล้าง HTML เก่าก่อน render
            $("#ib_scope_wrapper").empty();

            // Loop ชั้นแรก (mainCategoryText)
            groupedArray.forEach(group => {
                let html = "<tr>";
                html += `
                    <td style='vertical-align: top;'>
                        <span>${group.mainCategoryText}</span>
                    </td>
                    <td style='vertical-align: top;'>
                        <span style='visibility:hidden'>${group.mainCategoryText}</span>
                    </td>
                    <td style='vertical-align: top;'>
                        <span style='visibility:hidden'>${group.mainCategoryText}</span>
                    </td>
                `;
                html += "</tr>";

                group.subCategories.forEach((subCategory, subIndex) => {
                    const subCategoryArray = subCategory.subCategoryText.split(',').map(item => item.trim());

                    html += "<tr>";
                    html += `
                        <td style='padding-left:15px; vertical-align: top;'>
                            <ul style='list-style-type: none;'>
                    `;

                    // Loop สร้าง <li> สำหรับ subCategoryText
                    subCategoryArray.forEach(subCat => {
                        html += `<li>- ${subCat}</li>`;
                    });

                    html += `
                            </ul>
                        </td>
                        <td style='vertical-align: top;'>
                            <span>
                                <table class='table' style='border: none;margin-top:-15px'>
                    `;

                    // Loop ชั้น scopeTopics
                    subCategory.scopeTopics.forEach(topic => {
                        html += `
                            <tr>
                                <td style='vertical-align: top;'>
                                    ${topic.scopeTopicText}<br>
                                    <table class='table' style='border: none;'>
                        `;

                        // กรอง transactions ที่ ib_scope_detail_id ไม่เป็น null
                        const validTransactions = topic.transactions.filter(transaction => 
                            transaction.ib_scope_detail_id !== null
                        );

                        validTransactions.forEach(transaction => {
                            const detailArray = transaction.ib_scope_detail_text.split(',').map(item => item.trim());
                            html += `
                                <tr>
                                    <td style='vertical-align: top;'>
                                        <ul>
                            `;

                            detailArray.forEach(detail => {
                                html += `
                                    <li>${detail}</li>
                                `;
                            });

                            html += `
                                        </ul>
                                    </td>
                                </tr>
                            `;
                        });

                        html += `
                                    </table>
                                </td>
                            </tr>
                        `;
                    });

                    html += `
                                </table>
                            </span>
                        </td>
                        <td style='vertical-align: top;'>
                            <span>${subCategory.scopeTopics[0]?.transactions[0]?.standard || '-'}</span>
                           
                        </td>
                    `;
                    html += "</tr>";
                });

                $("#ib_scope_wrapper").append(html);
            });
        }

    </script>
     
@endpush
