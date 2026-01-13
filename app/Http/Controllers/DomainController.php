<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function check(Request $request)
    {
        $domain = strtolower($request->input('domain'));
        
        // Validation
        if (!preg_match('/^[a-z0-9][a-z0-9-]{1,63}\.[a-z.]{2,6}$/', $domain)) {
             return back()->with('error', 'Tên miền không hợp lệ (VD: webappbacninh.vn)');
        }

        // List of WHOIS servers
        $whoisServers = [
            'vn' => 'whois.vnnic.vn',
            'com.vn' => 'whois.vnnic.vn',
            'edu.vn' => 'whois.vnnic.vn',
            'gov.vn' => 'whois.vnnic.vn',
            'net.vn' => 'whois.vnnic.vn',
            'org.vn' => 'whois.vnnic.vn',
            'com' => 'whois.verisign-grs.com',
            'net' => 'whois.verisign-grs.com',
            'org' => 'whois.pir.org',
            'info' => 'whois.afilias.net',
            'io' => 'whois.nic.io',
            'co' => 'whois.nic.co',
        ];

        // Determine TLD
        $parts = explode('.', $domain);
        $tld = end($parts);
        // Check for double extension like .com.vn
        if (count($parts) > 2) {
            $secondLevel = $parts[count($parts) - 2] . '.' . $tld;
            if (array_key_exists($secondLevel, $whoisServers)) {
                $tld = $secondLevel;
            }
        }

        $server = $whoisServers[$tld] ?? 'whois.iana.org';
        
        // Check status
        try {
            $status = $this->queryWhois($server, $domain);
        } catch (\Exception $e) {
            $status = checkdnsrr($domain, 'ANY') ? 'taken' : 'available';
        }

        // Logic for suggestions if taken
        $suggestions = [];
        if ($status === 'taken') {
            $nameOnly = str_replace('.' . $tld, '', $domain); // very basic split
            $suggestions = [
                $nameOnly . '-bn.' . $tld,
                $nameOnly . 'store.' . $tld,
                $nameOnly . 'online.' . $tld,
                'my' . $nameOnly . '.' . $tld,
            ];
        }

        return view('frontend.domain.result', compact('domain', 'status', 'suggestions'));
    }

    private function queryWhois($server, $domain)
    {
        $fp = fsockopen($server, 43, $errno, $errstr, 5); // 5s timeout
        if (!$fp) {
            throw new \Exception("Connection failed");
        }

        $out = $domain . "\r\n";
        fwrite($fp, $out);

        $response = "";
        while (!feof($fp)) {
            $response .= fgets($fp, 128);
        }
        fclose($fp);
        
        $response = strtolower($response);

        // Keywords indicating "Available"
        $availableKeywords = [
            'no match', 
            'not found', 
            'no entries found', 
            'status: free',
            'domain not found',
            'is available' 
        ];

        foreach ($availableKeywords as $keyword) {
            if (strpos($response, $keyword) !== false) {
                return 'available';
            }
        }

        return 'taken';
    }
}
