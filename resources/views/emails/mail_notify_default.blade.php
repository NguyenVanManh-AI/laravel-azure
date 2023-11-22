<!DOCTYPE html>
<html>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<head>
    <title>Thông báo từ hệ thống</title>
</head>

<body>
    <div style="
    background-color: rgb(240, 255, 240);
    justify-content: center;
    border: 2px solid silver;
    margin-bottom: 3px;
    padding: 10px;
    text-align: center;
    ">
        <p style="font-style: italic;">Xin chào bạn ! Bạn có một thông báo đến từ hệ thống Elister Health Care !</p>
        <p>{{ now() }}</p>
    </div>

    <h3 style="text-align: center;">
        <span style="font-style: italic; color: rgb(40, 167, 69);">{!! $thongbao !!}&nbsp;</span>
    </h3>
    <div>{!! $content !!}</div>

<div style="
    background-color: rgb(240, 255, 240);
    justify-content: center;
    border: 2px solid silver;
    margin-top: 3px;
    padding: 20px;
    text-align: center;
    ">
    <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng gửi email cho chúng tôi theo địa chỉ <a href="mailto:help@elisterhealthcare.com">help@elisterhealthcare.com</a> hoặc truy cập <a href="FAQs">FAQs</a> của chúng tôi.</p>
    <p>Bạn cũng có thể trò chuyện với người thật trong giờ làm việc của chúng tôi. Họ có thể trả lời các câu hỏi về tài khoản của bạn hoặc giúp bạn giải đáp các thắt mắc khác.</p>
    
    <div style="width: 30%;height: 2px;background-color: silver;margin: auto;"></div>
    <div style="margin-top: 20px;margin-bottom: 10px;font-size: 30px;">
        <span class="facebook" style="margin-right: 5px;margin-left: 5px;cursor: pointer;"><i class="fa-brands fa-facebook"></i></span>
        <span class="instagram" style="margin-right: 5px;margin-left: 5px;cursor: pointer;"><i class="fa-brands fa-square-instagram"></i></span>
        <span class="twitter" style="margin-right: 5px;margin-left: 5px;cursor: pointer;"><i class="fa-brands fa-twitter"></i></span>
        <span class="youtube" style="margin-right: 5px;margin-left: 5px;cursor: pointer;"><i class="fa-brands fa-youtube"></i></span>
    </div>
    <style>
        a {
            text-decoration: none;
        }
        .facebook {
            color: #1773EA;
        }
        .instagram {
            color: #F66300;
        }
        .twitter {
            color: #1C9CEB;
        }
        .youtube {
            color: #F70000;
        }
        #div_img img {
            max-width: 150px;
            margin-left: 10px;
            margin-right: 10px;
            cursor: pointer;
        }
    </style>
    <p>Bạn đã nhận được email này với tư cách là người dùng đăng ký của <a href="https://react-vercel-95yac2b5v-vanmanh-react.vercel.app/">Elisterhealthcare.com</a></p>
    <p>Bạn có thể hủy đăng ký nhận những email này tại đây.</p>
    <p>54 Nguyễn Lương Bằng, phường Hòa Khánh Bắc, Quận Liên Chiểu , Đà Nẵng , Việt Nam</p>
    <p>Mã số công ty : <a href="tel:+01236000333">01236000333</a></p>
    <p style="font-weight: bold;">© Elisterhealthcare, Inc</p>
    <div id="div_img">
        <a href="https://react-vercel-95yac2b5v-vanmanh-react.vercel.app/">
            <img src="data:image/png;base64,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" alt="">
        </a>
        <a href="https://react-vercel-95yac2b5v-vanmanh-react.vercel.app/">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASQAAACSCAYAAADsHbcwAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAABfRSURBVHgB7Z1djBvXdcfPvcP9sB1ZY8kJAhSpKaBoAiN2Vn1J/RF4F34q0sAroBAC9EG7UlGjsGVRMNCXPmj1EBQo4IprKS1gVCL1lCIvWsEtijo1lm4gCS1qSEaMIhbgmEkcOZEtYeSV94ucuTlnZrjL5ZLD+SRnOOdnjLUkh8OZOzP/Offcc84VwMRGcXlZ32ONz4IURwQoXVmw+LPnn6n6+vKPf6x9af3r+xvj67pmqj1NTa1uyj0fwuFvbgLD5AQBTGRIiB6GyRMKzBI2qd7+mQJr5v2Z79S6frFDhDo/NjWxsvH9b98EhskJBWBC843l5eI4TB4hIULh0bvpu2XJ4o43UIQAHt87YX3xaKEhHrS0NU0zu29fQUEDhskRLEgh+OZPfjotCtoplIxpFCLoa2g6IvTApLnyqNbQdAX3baFR+J8XYqNpAsPkCBakALQLEfQRkxYoKfsfahSfcERIgCNg/hDjFvuPmFzBgtSHbf+QNUcv/QpRi2bD2oPfDdX1EqZoAsPkCBakHrQ7qh3/0OARqsBdNiZXsCB14HTL5BH8c86XfyhBNMvkLhuTK1iQXHb6h9LBSrPBgsTkilwLkt0ts8bmKJARPUNTQf1DDMPESy4FaYd/SIqh+Id8sUffAIbJEbkSJLdb9gLgiFmvQMZUwWkjTM7IhSDtjh9Kf8aMAMkjbEzuGFlBak90DRLImBoUR2kz+WPkBGlHoqsEPauO6qapsf+IyR0jI0iU6DoGEycy4x/qB1tITA7JvCBl0T/kBzHBPiQmf2RWkJ74r/+eBa1wIpP+IR/I5hh32ZjckSlB2l0IbXQDGaUaYwuJyR2ZEKTdia6jX+jyvsmlR5j8kWpByqMQbWF9wRYSkztSKUi5FqIWeyTXQmJyR6oE6fG3rkxpY0CO6tncCtEWH3OXjckdqRCk3aU/eDIUOHyYu2xM7hiqIIWpUZ0HhGKHNpNPBi5IeRq6D0ujMMH+IyaXDEyQ2FHtH57+iMkriQtS4DnMGJ7+iMktiQkS+4fCw9MfMXkldkF64u0rcyABhSj4HGaMA09/xOSV2ARppxAxUeDpj5i8ElmQ3HnMKsBCFBs8/RGTV0ILUhrnMWMYJtsEFiRn+H7ilAJVYh9RQvD0R0xOCSRItlUEsoJiVAQmOXj6IyanSL8rPrl89Qz6ipaBfUWJwtMfMXmmr4VExfMLMH4JraIpYJKHi/szOcZTkJyZPMbZKhogPP0Rk2d6dtlYjIYEW0hMjukqSCxGw4OnP2LyTFdBGoMJDnQcEjz9EZNndgnSk8tXONgxRiwlbwVZn9NGmDyzQ5Coq6YAFoAZGpw2wuSZHYKEfqNTwMSKaRVWAn1hz61VYJicsiVIZB3hP3PAxMpm0/QtSHZQJBf3Z3LMliCxdZQMK2vyE7/rNpuKrSMm19iCxNZRMlgWBHNoSy1Y945hRgxbkMas8WlgYkeB8m0dEaub6/eBYXKMdP//AjCxE3TInx3aTN5p+ZCmgYmdpqVu+l5XG1tlhzaTd+Tjy1coi18HJnaam+BbkLRmg/1HTO6RmmkWgUmE+2vStyCtbTTvAcPkHAmCraMksJT6ZHVT+LJ67PijY8+yhcTkHt8VI5lgWJb8wO+6pjQNYBiGBSkpGk31rt9119cnWZAYBpEKRBGY2NkwC74Eye6uzR9kQWIYYAspEZSClTuGvyF/7q4xzDYsSAmADm3fo2vrq+YdYBjGJvJU2sxuNhui5me9pqZtwLE/5dE1hnEhC4mH/WNmY9Of/0iqJueuMUwbUgjBghQjFH90Z8Vfl21d2xMs141hRhz2IcWMacn/87OeJdUdnjKbYXbCghQzX6xqP/KzHltHDLMbCULsBSYWLAtu+umusXXEMN1BC0mxDykmNjaBrSOGiQB32WKCnNm37mpv9l1xvfkJW0cM0x2OQ4qJjQ3xRr91mppa3Tz2LFtHDNMDFqQYoMqQt+4WPK0jCoLclA98CAzD9KSgAHQBTFgUNuDde/LVfuttyq9+CIe/xl01hvFACuDAyChsmvBGv7nXtPtrv0QxWgOGYTzhLlsETEu8+fFt6ek7IjFaeXHmM2AYpi88yhYSZ1RN/GOvzxvaeJPFiGGCQRYSd9kCQmL0mSH/erNHzWxyYDfklz9svPgn3E1jmADIFYN9SEFoiVEvv5HZFCs4mnaTfUYME5zC53a9QgF7dAWMN/3EiIIeNzjOiGFCYzu1SZSaTQGPPMqi1AuT8tRweL+bGJFVtPHAQ3X4S47AZpgobI2yrd5HR+ymgEe/qkCyq3sHjSb86LeGfKPTZ2QLUbPxCc+pxjDxsGPYv4HP99u3HFEqcEAAoL24stmANz7+dLukCI2eyfW1+xumus1CxDDxskt2zCbAZ79lUbKUqH1mwGutLlrTVKuFhnmvsedXv4O5wyYwDBM7XSWnJUr7v6JgbBxyhWmpd1fXxBu378l3bWuosW0NsYOIYZKlpw3UEiVydE8+CCPPthAV3rV9Q2LSgLEP7rA1xDCDw7NTZlkAd24L0PcpeOhhGElaQvSb+w/+T2F15e6aUgb7hhhmOPjyEhl3hS1OoxSr1BKiT+6M1WxraPKDO42jbA0xzDDx7bYelQBKEqIvmoV/uv2bjZ+sKQ2toW+zNcQwKSHQOBqJkmUJ2Lsve6JEQrS+KV/7xadfepusIWBriGFSR+CB/fufOwGU+76SjQBK7Gr+b6Np/v3Nj+XbcOxptoYYJsWgIKETN2CRto319AdQKlC1TUv7h5t3fv0WHGZriGGyAMqJIO9Q4Iz/tAZQkhDh/0+/P/OdGjAMkykiSUmaAihZiBgm+0S2bYYdQMlCxDCjQyydrWEEULIQMczoQYJkQEwMIoCShYhhRpeCQKe2gvgEJKlYpSwLkT53hgYNihBv/XLDqJ68ATnFbdMpkGMvgFBToFQRB2iKbasYIKCO/9ZBWe/hRVnD9qoBk2rE/sq1ZbzZpyFmJiYhllilrFtE+rGzs3gQlyAJFNSMyvEZr1Xwxi3iTbuMKxd7rLJgXDh+GmLGPe4KdBdhg86pceGVMgTEPZ4T+P05CC7wFOKyBFbjNIpT3fM3tEIF938aEkXUcV9meu3L2JuXTwilSiisRYgThSItRHXzey94nnc8h8sxtAE9GG6AEjfwWC/3eyhIpdQvIQFasUrNJoRlyQQ4+P7MszPZ7p6JU5AUAqb1Y+ce815HljzEiChBMqBo9BQMPWi7kEWkH339DMjCR3g8JQhnbeq2kOE29KPnKrbwdEPKueTFiFAkrh7toGZjFyPC3qZa8FoF22Y6pjbQne3gOZOFZRK5nu0OCc/L1hqBCyJKZBEpsGZ+NvPMof+feSb7XRLlKQbRMdUBz8+l/BZ4k8ysM/2P2/fv4gVMXbPreCfFKJ4tYXp99zZVAiLAOJA4ycJ1ff7ckW4fSydSOzlIlD5FS2l9te+qVRKi7FtEu+BppiLgdNEKy32svAgkaMFGRDUlWKZIbBkiOvr9qvrc2enODyQqVqKCRLTCAlaM3Y1AFpEG8gBaRPM8csa0Yzuubf9XkqKuEr/+I6ESXIaNFBV3cGKLgSZ9dBmBW0KL6BAwTDdk4URylpGLECchpWiUgZmgIRPevRsX5EMjHycstN6RQtpDowODqgVsO7vNi8AwPRFzkCynjfPHlyClSHxua1ZySzqQJ9pfDSUtlqZbImf3A5OyCExEmnUYQZywAb/WkarjsPJlXP+GmyxOd3MRrZ/n8L3p7tUsVN248MpC182JAT6klXmv10f7bY9KMn0rqUS9v1t3IOjkSzKqx2v0ogCmaYA2eF0iZ/f9+/LMvvNX4e6xpwPHo2QHvFlAFPusQzdRMF+GwBtPiYte8TSZRllT/gaB5bxx4aVqjw/Ljh9Kw+FzcWRrGJviYkyzt6vAssogJH4P2kcoW6LmnCe/Q+L0W/SddnEVou5u4x08zp7X/oRQ7wmVQJ8Nrx1lRe+dfG3/HnjxeaeJ7q1u7Pr813dW4F+v/bz/hqTCcw01+rNgKmEMtc6ahDP7Ktf23p1/KvbgvMxgmQdHVljCQkPv/e5FATXjfE8xssF2JQGpugs6yv+5aFT/pu7jO54hBvrRxQW8ePuM0Imqcf7leQjJh3/xvaRixGLh+099AwXpSe91nv46zL52GbzZDk2RUCjUYeioBRSl1A6/JouosxiFJIQfpJ8Y+f9tq+ZjpXdghPlDtJD68cwf/4FtSXmirEdaf6aoCK1aeKRy9ZJeWea4HYbo34WlSPWjZ095Rf4yyXFvLaapU6XY2/qzYMw/Vd9XuQppAC30WQETRRSlQ8b8TB1ygSra0cKWWQWPeJvcWVFC+I3SXwBZWEAnOOVL1cBqkFVSc7tdTIJ08xt1Qn4kWjyx1JZjv+XNrgOkJlx+SsLEMorSTH5ESVCO1hmvNdASACcZUxwyqi9lP6WmH5a55LaJP4tZATpG0TkqC7bfBQWq5jehM608XPm3M/SQhgTAsbulz+f/PFIM1n/c+IXtQ9r74ETXz698cAuOX3y7/4aE2rqeW4KUtqdJMX+i5AcKJLOTVkM7SrMCWTjoOF7s7zjugZPQSXlTJRRzyjhHgVKXcai/CgMjYlAnHoOVnKFAwh1JkN7/+A780cnzto+o3Z/0K7SIqDv3uQ8LykbInYKkQNYF0DBrqiBRuq5XrqAojUCSbVyIhCOX0wQNv0scrgdRhGhQxjlaGmJWP3oOBU5VwWoupr1b1zClkWSOx+S//Ptj63/13cjVPnx1y7wwm7XWn7ZTWygzkRIkMaBLENf3nb+a6uFPJhlswbDMGSeWKy5sQV+g6gHdkjtThUp4SQWi2v5gsAXJUnGe8ARwYpUyGhYQbM47Zie2M59ESTmBc/Fhd3+Xe5XBSAN2iocpEluGjzKoWF77O7YgacqqQ+rJbKwSC1JESJScyphq3vYFxYldBuOHaXNX5AS5q3KnLUimNpYRHw2J0pUKMLmEHNLGeRQmSx5Ei+libF05YZ2BFFJoUipFcsskDJXTxoWXd6XN2IJEsUiQ9rowW4i5fZWr13MbQKlU7iskUNgDWkxzKFAHwALqzi1GspwowDKd/qQiJEfdiMGhHQ7KPzy+0O2TtqxaO+EvK6brlDMCNzJhAafxxkIrVfUQWTcZEwr12FIfRgQ3S5wWdyaSwpSdrGln+geI4RHWbGs7qaEhZtBMKkLcSGkYL3035l4RGTQ+/KUUelHpnX+4LUgK3sMnRZb60iMSqyTqaLouABMZd7Sm5i5lO6VEFGjWjhN9vyy0FyD2CQ9aD5JwGCf/rA4w2HplwRAnwdKWWg9Jpz658O7+4oOCzkuvzIOtXDZLWVmM9XFF6Qo7JXthWWkN6Ugc1xlesn1OfVE8+BAYy2i32O1prWzfnic6PiR6+oG3k2ulqEE2QVESy/qFa6kdvk07nXWN0wbNOqIfPfeRfvSs8py+qAd2qk3/3DgWpMB0CdJVTbIyvf3RHj67rS4bRUPjCJa/fmD60CUO3+6rXCtmr67SVnJt0EEFWv9GLEm3slDBfXBLZfT0Yxkd+1gbRMKvOzHkpe2L356+aA6FqQpWY9HP7L32NpTKnBX95R+8NSXA9O8HC8BmA6rGgt0ljBXfKT8C6PNa59s7S0VSGVABGbY07FglyJ4o2cm1EBzbR3EAomOnVbjb7L3Wzn008EY/kHj6hZ1g2y1dZkuY6kAXttWgKmBG20LCSmL2HDiz3HrjVHZMFUJY2LURiQjp+Lh9n8dx7ezGTvmRXhOFuqVjXp/rzC3cUQ/JEmYVMg+J0tVUxpXED1pX/WauTQ6nNGySP3D0LD1F+/wGiZUtTpfs+dtk4bo9u63z7yXfs9xa6Sum1rQENCxIailCQjgPKWux/5ryVKe7YIcgGfM0L1pW4pE8KeWm2Fu/mWsTJblEX3p6Qtv0OInjUdt6WDRMZZAoJbUkimW3Zx8t2ZoGaYtdFSMtECNR25rqyLhhAUVgMogcYJqQneBZh7RBJXrNBJcECWAlnWi3knYJkjH/dFmlOvYhEFMpEKVRsDiHwKCG4VW9M8EzLVimtCdWTWpJHF9WEnX9t62krjW1FZijVABsyLFKI9EFHjwWHAJIugoFZZubh9JaHpgMJKWSW5ImjJXUVZBsX5JSPjaUGexYpf2Vq4k6YfOHiCK2nt+1U0IskwIaTycjTMoua+InbKAHiT9olElWUnLL5N+9/Vi04/Nx/gNaST1nHbl79JmSgMwGS3ZDx4fCpSEUe0tQ2FW9NeNnT0RS59C2LpY8Vuhz3GIJ+kBPWErCtJNoYys9Ylusp9258KIM9deh341mmTWIglLvJFegTdXXf/C8VxR/3ftB0Pf82zhF9pTvUrmeHUkapRIwcV2kZwKAmBALg4xVcqJSYxyRku4wtmVW/cQBxf77NuZSv9/u/buiv5D23CYFSmrTKLSUe1Z0ivv3A28eitRW6rLfNvO9L5TM29XfFf4Y2xn/2/+clTL+YOXJ9S+WjPKhPufP6/j6n//d28Lz1pXtturr2dIr14oC1DKLEpNWnAJrlnvTkAAKNzhS2gtXSMgOvlztZClJNVHOdhR3N1TVgs2TxvwMO54ZJgUEGvvTK1dLEtSpEasTfcOCjRxNTMkw6SVwMAJ14aRSCyNmLdVRlHgOOIYZMqGjo1rCpAQ8NyL+JRYlhhkysYRr6pWfTkulzQohvqXsCScz26UzLFA8MSXDDIkBxI9vo1euo1CtJyRWNMpixbJtJ8k4Po6XSnMawFK5XN7lPH+5VJotANS6feZFqVQqmkDTRe+m12/5xd0nA7dRgwh4Hbcf8BinTI8675rTbnXwv71iE7d3rlxe6vKZjr81e7ZcrsKAwfaexhtxChfdQp9mt/0LSuv6iHotdLvOKLXsXMhro9XO3T6j8xmmCE9ojPmD7nBsvpAAlYamLUOXY8eTQKVSKFWnBgHAG6vofrfbxXvDXQJDFyAFkLr5jJEqCdBxg7OdGoRjCrfR7qucBue4Wu1YhwB5l33aTHf3twoD5ESpRL85i+1NpV8N2j987wyK00wQse0Et3cKj2fOdEqvlCE807gdHMjacQ6P0H7jPh7CfQx6nXm1c32ggsTEjrFYLseddzgNzsUyjeI0HdVKikLZsVaqrdd4E6imps3+8LXXRqJO+CulEt3oZB0dXNwWnwU8zmXl3LQzEJ5ptLZO4s1PAaRRBImod15nuO+0TRL3UPvY67qVwDBt0JMVb5CL9MS2IMA0QkxgsJ3nsI1Pd1pCws7fgyJ1byAE1AUEx9qq4r9TYbfjhXQfWhAzLEjMFuSzoX/JKqILTsCoBcKmB7etqQu5q8tD7Y8WxIGwvh/c5hHLNkLKhvtgKUHMWDTvHcRf9pe7bAOiYJpVNMW7fVSE8OiuD2ILvPjeQ8dsKBMdL16qg1yjv+mpjdu+MexuWwJQmy13vqkGPOsI+rN0FA4I4YPxhKwhPJZZzbGyyOJYcrt/CxCeIg1QtF7gNp8DpWaF4/sMRed1S1A3jgVpQAjHaVnvfF9FjOHC7b7T/lqLNmhA/oxDrRcobpe7ODQzj9sl6sSuBgHZh7rZN1rdQHqY4M2vR32wtMUaknDP4etHoozedV63LViQBgSNsnVzxvawmvxilGMapnb9DvYIW8c+0cWsR7n4UkbXcAYaXYQBgjdenaqAvPTqq4/F6aRXTjd7Cs/hR+3vu/7AGoSj/nq5vNB6gdum7tpUhO1Br+uWBYmx0ZwLeUl01DEiJzcOHc9B9JEapg23S2xopl2ddaH9M3o44PlYDmqFuCEbLSu3/XtFcEbEYvElobhdTMpyZkFiWn4HMsMPdvo00HcwFdPQMdOB23U8he1fbXWx3HNhx+mEsEqn8buXF7sEVlJsU1z+QBq9U85+x+5fZEHKNsVO05xAi+ZkwGjflt9hl4PVvfjOhO22KSfQz+jYv8U4opGzDrZn2RWgj7CNqD1oVMw+FyhWvqsstqBBCdWjUqf7fixWDV0Hr5RK5BM9EnZ73a5bElMWpAFAUbe9/ATuSEXgkZaC850Zj8+CcKPdmd0OXXx404QKfhPx7d/W9qL4W9zfPdTjY0NEC0QMBbbvAllI4PhkyGG8GGbkzY01Onm2h8UiHQs3zEOgJrqcL3d7RQhOz3bG9+u/B0uOG4Qp1jrxAAAAAElFTkSuQmCC" alt="">
        </a>
        <a href="https://react-vercel-95yac2b5v-vanmanh-react.vercel.app/">
            <img src="data:image/png;base64,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" alt="">
        </a>
    </div>
</div>
</body>

</html>
