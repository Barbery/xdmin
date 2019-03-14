@extends('layouts.admin-app')

@section('inlineStyle')
    <style>
        .page-content {
            height: 600px;
        }
    </style>
@endsection

@section('content')
<div class="col-xs-12 col-sm-6 widget-container-col ui-sortable" id="widget-container-col-2">
        <div class="widget-box widget-color-blue ui-sortable-handle" id="widget-box-2">
            <div class="widget-header">
                <h5 class="widget-title bigger lighter">
                    <i class="ace-icon fa fa-table"></i>
                    当日统计
                </h5>
            </div>
            <div class="widget-body">
                <div class="widget-main no-padding">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="thin-border-bottom">
                            <tr>
                                <th>
                                    <i class="ace-icon fa fa-trophy"></i>
                                    统计项目
                                </th>

                                <th>
                                    统计数
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>当日订单总数</td>
                                <td>{{ $todayTotalCount ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td>当日订单支付成功数</td>
                                <td>{{ $datas[1]['count'] ?? 0  }}</td>
                            </tr>
                            <tr>
                                <td>当日收款总额</td>
                                <td>{{ money_format('%.2n',($datas[1]['total_amount'] ?? 0)/100) }}元</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
