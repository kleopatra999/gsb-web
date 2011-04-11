    <div id="side-bar">
      <div>
        <h2 class="sideBarTitle">
          Navigation:
        </h2>

        <ul>
          <?php if($op == "index" && (!strstr($_SERVER['REQUEST_URI'],"news"))) { ?>
          <li>
            <a href="/"
                title="Home Page"
                class="thisPage">Home</a>
          </li>
          <?php } else { ?>
          <li>
            <a href="/"
                title="Home Page">Home</a>
          </li>
          <?php }
                      if ($op == "download") { ?>
          <li>
            <a href="/download/"
                title="Download and Installation Info"
                class="thisPage">Download &amp; Install</a>
          </li>
          <?php } else { ?>
          <li>
            <a href="/download/"
                title="Download and Installation Info">Download &amp;
                Install</a>
          </li>
          <?php }
                      if($op == "screenshots") { ?>
          <li>
            <a href="/screenshots/"
                title="Screenshots"
                class="thisPage">Screenshots</a>
          </li>
          <?php } else { ?>
          <li>
            <a href="/screenshots/"
                title="Screenshots">Screenshots</a>
          </li>
          <?php }
                      if ($op == "development") { ?>
          <li>
            <a href="/development/"
                title="Development"
                class="thisPage">Development</a>
          </li>
          <?php } else { ?>
          <li>
            <a href="/development/"
                title="Development">Development</a>
          </li>
          <?php }
                       if ($op == "lists") { ?>
          <li>
            <a href="/lists/"
                title="Mailing Lists"
                class="thisPage">Mailing Lists</a>
          </li>
          <?php } else { ?>
          <li>
            <a href="/lists/"
                title="Mailing Lists">Mailing Lists</a>
          </li>
          <?php }
                    if ($op == "support") { ?>
          <li>
            <a href="/support/"
                title="Support &amp; FAQs"
                class="thisPage">Support</a>
          </li>
          <?php } else { ?>
          <li>
            <a href="/support/"
                title="Support &amp; FAQs">Support</a>
          </li>
          <?php }
                      if($op == "about") { ?>
          <li>
            <a href="/about/"
                title="About the Project"
                class="thisPage">About</a>
          </li>
          <?php } else { ?>
          <li>
            <a href="/about/"
                title="About the Project">About</a>
          </li>
          <?php }
                      if ($op == "donate") { ?>
          <li>
            <a href="/donate/"
                title="iDonate to the GSB Project"
                class="thisPage">Donate</a>
          </li>
          <?php } else { ?>
          <li>
            <a href="/donate/"
                title="Donate to the GSB Project">Donate</a>
          </li>
          <?php } ?>
        </ul>
      </div>
    </div>
