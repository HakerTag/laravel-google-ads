<?php

use Edujugon\GoogleAds\Reports\Report;
use Google\AdsApi\AdWords\Reporting\v201609\DownloadFormat;
use Google\AdsApi\AdWords\Reporting\v201609\ReportDownloader;

class ReportTest extends PHPUnit_Framework_TestCase {

    /** @test */
    public function report_fields()
    {
        $list = (new \Edujugon\GoogleAds\Reports\Fields())->of('CRITERIA_PERFORMANCE_REPORT')->except(['Parameters','AccountCurrencyCode'])->asList();

        $this->assertInternalType('array',$list);
        $this->assertInternalType('array',(new \Edujugon\GoogleAds\Reports\Fields())->reportTypes());
        $this->assertInstanceOf('stdClass',(new \Edujugon\GoogleAds\Reports\Fields())->of('CRITERIA_PERFORMANCE_REPORT')->asObj());
        $this->assertArrayNotHasKey('Parameter',$list);
    }

    /** @test */
    public function format()
    {
        $this->assertInternalType('array',\Edujugon\GoogleAds\Reports\Format::list());
        $this->assertEquals('CSV',\Edujugon\GoogleAds\Reports\Format::get('csv'));
    }

    /** @test */
    public function wrong_format()
    {
        $this->expectException(\Edujugon\GoogleAds\Exceptions\ReportFormat::class);
        $this->assertEquals('CSV',\Edujugon\GoogleAds\Reports\Format::get('asdf'));
    }


    /** @test */
    public function report_set_select_fields()
    {
        $report = (new Report())->select('a','b','c');

        $this->assertEquals(['a','b','c'],$report->fields);

    }

    /** @test */
    public function get_report()
    {
        $report = new Report();
        $obj = $report->select('AccountCurrencyCode','CampaignId','AdGroupId','AdGroupName','Id', 'Criteria', 'CriteriaType','Impressions', 'Clicks', 'Cost', 'UrlCustomParameters')
            ->from('CRITERIA_PERFORMANCE_REPORT')
            ->during('20170101','20170210')
            ->format('XML')
            ->where('CampaignId = 752331963')
            ->getAsObj();

        $this->assertInstanceOf(SimpleXMLElement::class,$obj);

    }

    /** @test */
    public function get_all_types()
    {
        $report = new Report();

        $this->assertInternalType('array',$report->getTypes());
    }
}