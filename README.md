
# mac-to-oui
 A simple MAC Address to Manufacturer Information json response.
 
### Requierments
PHP based webserver

### Usage
On first run the script will download the latest oui.txt file (The source of which can be changed within the script)

To recieve a Json reply with the MAC address manufacturer simple pass the MAC address in the query string.

For example: https://example.com/**?mac=08:00:20**

would return

    {
        "query": "08:00:20",
        "hex": "08-00-20",
        "base16": "080020",
        "company": "Oracle Corporation",
        "data_source": "November 27 2019 20:08",
        "querytime": "0.0522ms",
        "peakmemory": "379KB"
    }
Note: you can see the data_source **date** in the response.

### Refresh the OUI data file
Periodically you may wish to update the oui.txt file from the orginal source.

To acheive this include **&refresh=yes** on the end of a request
This request will take longer of course as it will go and re-download the oui.txt file before responding.

For example: https://example.com/**?mac=08:00:20&refresh=yes**

---
*Disclamier: Use at your own peril, somme basic error checking is present, but could be improved on (if anyone wants to add suggestions and code improvements please feel free to submit them)*
