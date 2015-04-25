function CreateXmlHttp()
{
    var xmlHttp;
    // Probamos con IE
    try
    {
        // Funcionará para JavaScript 5.0
        xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch(e)
    {
        try
        {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch(oc)
        {   
            xmlHttp = null;
        }
    }

    // Si no se trataba de un IE, probamos con esto
    if(!xmlHttp && typeof XMLHttpRequest != "undefined")
    {
        xmlHttp = new XMLHttpRequest();
    }

    return xmlHttp;
}