@extends('layouts.app')
@section('content')
<div class="row">
    @include('layouts.partials.side')
    <div class="col-9">
        <div class="row p-3 mb-2">
            <div class="col">
                <h4>Application Version</h4>
                <ul>
                    <li>The latest Application version is {{ $versioninfo['app']['latest'] }}</li>
                    <li>You are running Application version {{ $versioninfo['app']['current'] }}</li>
                </ul>
                <h4>Database Version</h4>
                <ul>
                    <li>The latest Database version is {{ $versioninfo['db']['latest'] }}</li>
                    <li>You are running Database version {{ $versioninfo['db']['current'] }}</li>
                </ul>
                <p>
                    The use of this software is subject to the terms of the <a href="http://mozilla.org/MPL/2.0/" target="newwindow">Mozilla Public License, v. 2.0</a>.
                    You can download the most recent code <a href="https://www.simplerisk.com/download" target="newwindow">here</a>.
                </p>
            </div>
        </div>
        <div class="row p-3 mb-2">
            <div class="col">
                <p><a href="http://www.joshsokol.com" target="newwindow">Josh Sokol</a> wrote this Risk Management system after being fed up with the high-priced alternatives out there.  When your only options are spending tens of thousands of dollars or using a spreadsheet, good risk management is simply unattainable.</p>
                <p>Josh lives in Austin, TX and has four little ones starving for his time and attention.  If this tool is useful to you and you want to encourage him to keep his attention fixed on developing new features for you, perhaps consider donating via the PayPal form on the right.  It&#39;s also good karma.</p>
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="payformmargin">
                    <input type="hidden" name="cmd" value="_xclick">
                    <input type="hidden" name="business" value="josh@simplerisk.org">
                    <input type="hidden" name="item_name" value="Donation for Risk Management Software">
                    <input type="hidden" name="no_note" value="1">
                    <input type="hidden" name="currency_code" value="USD">
                    <input type="hidden" name="on0" value="Project Details">
                    <div class="form-group">
                        <label for="amount">Enter amount</label>
                        <input type="text" name="amount" placeholder="50.00" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="os0">Your message for Josh</label>
                        <input type="text" name="os0" class="form-control" placeholder="Thank you for this wonderful tool"/>
                    </div>
                    <button type="submit" name="PaypalPayment" class="btn btn-primary">
                        <i class="fab fa-paypal fa-fw" aria-hidden="true"></i> Donate
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection