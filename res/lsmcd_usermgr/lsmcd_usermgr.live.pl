#!/usr/local/cpanel/3rdparty/bin/perl

####################################################
# Copyright 2018 LiteSpeed Technologies
# https://www.litespeedtech.com
#####################################################

use strict;
use warnings;

use lib '/usr/local/cpanel/';

use CGI;
use Cpanel();
use Cpanel::LiveAPI();
use Cpanel::Template();

Cpanel::initcp();

my $cpliveapi = Cpanel::LiveAPI->new();

run() unless caller();

sub run {
    print "Content-type: text/html; charset=utf-8\n\n";

    my $cgi = new CGI;

    my $key;
    my %input;

    my $post = '';
    my $doVal = '';

    for  $key ( $cgi->param() ) {

        if ( $key eq 'do' ) {
            $doVal = $cgi->param($key);
        }
        else {
            $input{$key} = $cgi->param($key);
        }
    }

    if ( %input ) {
        $post = join("&", map { "$_=$input{$_}" } keys %input);
    }

    Cpanel::Template::process_template(
        'cpanel',
        {
            'template_file' => 'lsmcd_usermgr/lsmcd_usermgr.html.tt',
            'print'   => 1,
            'data' => {
                'doVal' => $doVal,
                'jsonPost' => $post
            }
        }
    );
}

$cpliveapi->end();