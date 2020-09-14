<?php
$stmtGuidance = $db->getGuidanceByIdAuthority(0);

?>
{!! $header !!}
<div class="container" style="padding-top: 5%">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <h1 class="my-4">How to go to Online Course <small>SBM ITB TK Low Center</small></h1>

                </div>
            </div>
        </div>
    </div>
    @if($stmtGuidance->count() > 0)
        <?php
        $dataGuidance = $stmtGuidance->get();
        ?>
    <div class="row">
        @foreach ($dataGuidance AS $index =>$guidance)
            <div class="col-lg-12 col-md-5 col-sm-6 portfolio-item">
                <div class="card h-100">
                    <div class="card-body">
                        <h4 class="card-title">{!! $guidance->title !!}</h4>

                        {!! $guidance->content !!}
                    </div>
                    <div class="card-footer">

                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endif

    @if(session('idMember'))
        <?php
        $dataMember = $db->getAccountDataByIdMember(session('idMember'))->first();
            $idAuthority = $dataMember->idAuthority;
        $stmtGuidance = $db->getGuidanceByIdAuthority($idAuthority);
        ?>
            @if($stmtGuidance->count() > 0)
                <?php
                $dataGuidance = $stmtGuidance->get();
                ?>
                <div class="row">
                    @foreach ($dataGuidance AS $index =>$guidance)
                        <div class="col-lg-12 col-md-5 col-sm-6 portfolio-item">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h4 class="card-title">{!! $guidance->title !!}</h4>

                                    {!! $guidance->content !!}
                                </div>
                                <div class="card-footer">

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
    @endif
</div>
{!! $footer !!}