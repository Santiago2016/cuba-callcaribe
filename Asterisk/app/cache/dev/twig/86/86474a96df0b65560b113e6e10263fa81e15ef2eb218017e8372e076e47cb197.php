<?php

/* TwigBundle:Exception:exception.json.twig */
class __TwigTemplate_45d31befa95c2208e1e1c35c967640acd8ae3f3ad9a30734db522b3c94d2ed90 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_90efb62296a6068a707ded4574ec605de44f8275035f3d9f920d3b49d6f516bd = $this->env->getExtension("native_profiler");
        $__internal_90efb62296a6068a707ded4574ec605de44f8275035f3d9f920d3b49d6f516bd->enter($__internal_90efb62296a6068a707ded4574ec605de44f8275035f3d9f920d3b49d6f516bd_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "TwigBundle:Exception:exception.json.twig"));

        // line 1
        echo twig_jsonencode_filter(array("error" => array("code" => (isset($context["status_code"]) ? $context["status_code"] : $this->getContext($context, "status_code")), "message" => (isset($context["status_text"]) ? $context["status_text"] : $this->getContext($context, "status_text")), "exception" => $this->getAttribute((isset($context["exception"]) ? $context["exception"] : $this->getContext($context, "exception")), "toarray", array()))));
        echo "
";
        
        $__internal_90efb62296a6068a707ded4574ec605de44f8275035f3d9f920d3b49d6f516bd->leave($__internal_90efb62296a6068a707ded4574ec605de44f8275035f3d9f920d3b49d6f516bd_prof);

    }

    public function getTemplateName()
    {
        return "TwigBundle:Exception:exception.json.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  22 => 1,);
    }
}
/* {{ { 'error': { 'code': status_code, 'message': status_text, 'exception': exception.toarray } }|json_encode|raw }}*/
/* */
